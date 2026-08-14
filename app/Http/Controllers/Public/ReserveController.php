<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Lawyer;
use App\Models\Payment;
use App\Models\Setting;
use App\Notifications\NewReservationNotification;
use App\Notifications\PaymentReceivedNotification;
use App\Services\ZarinpalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;

class ReserveController extends Controller
{
    private const CACHE_TTL = 300;

    public function index(Request $request)
    {
        $lawyerSlug = $request->query('lawyer');
        $lawyer = $lawyerSlug ? Lawyer::where('slug', $lawyerSlug)->firstOrFail() : Lawyer::first();

        if (! $lawyer) {
            return redirect()->route('home')->with('error', 'وکیلی یافت نشد');
        }

        $appointmentPrice = Setting::where('key', 'pricing.appointment_price')->value('value') ?? 500000;
        $currentMonth = (int) $request->query('month', Jalalian::now()->getMonth());
        $currentYear = (int) $request->query('year', Jalalian::now()->getYear());
        $calendar = $this->generateCalendar($currentMonth, $currentYear, $lawyer->id);

        return view('public.reserve', compact('lawyer', 'calendar', 'currentMonth', 'currentYear', 'appointmentPrice'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'lawyer_id' => 'required|exists:lawyers,id',
        ]);

        try {
            $lawyer = Lawyer::findOrFail($request->lawyer_id);
            $date = Carbon::parse($request->date);

            if ($date->isPast() && ! $date->isToday()) {
                return response()->json(['success' => false, 'message' => 'نمی‌توانید برای تاریخ گذشته نوبت بگیرید'], 400);
            }

            if ($date->dayOfWeek === Carbon::FRIDAY) {
                return response()->json(['success' => false, 'message' => 'روز جمعه تعطیل است'], 400);
            }

            $cacheKey = $this->slotsCacheKey($lawyer, $date->format('Y-m-d'));
            $slots = Cache::remember($cacheKey, self::CACHE_TTL, fn () => $lawyer->getAvailableSlots($date->format('Y-m-d')));

            return response()->json(['success' => true, 'slots' => $slots, 'date' => $date->format('Y-m-d')]);
        } catch (\Exception $e) {
            Log::error('Get Available Slots Error', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'خطا در دریافت ساعات موجود'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'selected_date' => 'required|date|after_or_equal:today',
            'selected_time' => 'required|date_format:H:i',
            'lawyer_id' => 'required|exists:lawyers,id',
        ], [
            'selected_date.required' => 'لطفاً تاریخ را انتخاب کنید',
            'selected_date.after_or_equal' => 'نمی‌توانید برای تاریخ گذشته نوبت بگیرید',
            'selected_time.required' => 'لطفاً ساعت را انتخاب کنید',
            'lawyer_id.required' => 'وکیل انتخاب نشده است',
        ]);

        if (! Auth::check()) {
            return $this->handleGuestReservation($validated);
        }

        try {
            return DB::transaction(fn () => $this->processReservation($validated, Auth::id()));
        } catch (\Exception $e) {
            Log::error('Reservation Error', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * بازگشت از درگاه زرین‌پال
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $lawyerSlug = optional($payment->payable?->lawyer)->slug;

        if ($request->query('Status') !== 'OK') {
            $payment->update(['status' => 'failed']);

            return redirect()->route('reserve.index', ['lawyer' => $lawyerSlug])
                ->with('error', 'پرداخت لغو شد یا انجام نشد.');
        }

        $zarinpal = new ZarinpalService;
        $result = $zarinpal->verify((float) $payment->amount, $request->query('Authority'));

        if (! $result['success']) {
            $payment->update(['status' => 'failed', 'gateway_response' => $result['raw'] ?? null]);

            return redirect()->route('reserve.index', ['lawyer' => $lawyerSlug])
                ->with('error', 'تایید پرداخت ناموفق بود. در صورت کسر وجه ظرف ۷۲ ساعت بازگشت داده می‌شود.');
        }

        $payment->update([
            'status' => 'paid',
            'ref_id' => $result['ref_id'],
            'paid_at' => now(),
            'gateway_response' => $result['raw'] ?? null,
        ]);
        if ($payment->payable && $payment->payable->lawyer) {
            $payment->payable->lawyer->notify(new PaymentReceivedNotification(
                $payment,
                route('lawyer.consultations.show', $payment->payable_id)
            ));
        }

        return redirect()->route('reserve.index', ['lawyer' => $lawyerSlug])
            ->with('success', 'پرداخت با موفقیت انجام شد. کد پیگیری: '.$result['ref_id'].' — نوبت شما پس از تأیید وکیل نهایی می‌شود.');
    }

    // ─── Private Helper Methods ───────────────────────────────────────────────

    private function generateCalendar(int $month, int $year, int $lawyerId): array
    {
        $firstDayOfMonth = new Jalalian($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->getMonthDays();
        $carbonFirstDay = $firstDayOfMonth->toCarbon();
        $startDayOfWeek = ($carbonFirstDay->dayOfWeek + 1) % 7;

        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear = $month === 1 ? $year - 1 : $year;
        $nextMonth = $month === 12 ? 1 : $month + 1;
        $nextYear = $month === 12 ? $year + 1 : $year;

        return [
            'days_in_month' => $daysInMonth,
            'start_day_of_week' => $startDayOfWeek,
            'month' => $month,
            'year' => $year,
            'prev_month' => $prevMonth,
            'prev_year' => $prevYear,
            'next_month' => $nextMonth,
            'next_year' => $nextYear,
            'booked_dates' => $this->getBookedDates($lawyerId, $month, $year),
        ];
    }

    private function getBookedDates(int $lawyerId, int $month, int $year): array
    {
        $lawyer = Lawyer::find($lawyerId);
        if (! $lawyer) {
            return [];
        }
        $cacheKey = "booked_dates_{$lawyerId}_{$year}_{$month}_{$lawyer->updated_at->timestamp}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($lawyerId, $month, $year) {
            $firstDayOfMonth = new Jalalian($year, $month, 1);
            $daysInMonth = $firstDayOfMonth->getMonthDays();
            $startDate = $firstDayOfMonth->toCarbon()->startOfDay();
            $endDate = (new Jalalian($year, $month, $daysInMonth))->toCarbon()->endOfDay();

            return Consultation::where('lawyer_id', $lawyerId)
                ->whereBetween('scheduled_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();
        });
    }

    private function handleGuestReservation(array $data)
    {
        session(['pending_reservation' => $data]);

        return redirect()->guest(route('login'))
            ->with('info', 'برای ثبت نوبت، لطفاً ابتدا وارد شوید یا ثبت‌نام کنید');
    }

    private function processReservation(array $data, int $userId)
    {
        $lawyer = Lawyer::lockForUpdate()->findOrFail($data['lawyer_id']);
        $selectedDate = Carbon::parse($data['selected_date']);
        $startTime = $data['selected_time'];

        if ($selectedDate->dayOfWeek === Carbon::FRIDAY) {
            throw new \Exception('روز جمعه تعطیل است');
        }

        $availableSlots = $lawyer->getAvailableSlots($selectedDate->format('Y-m-d'));
        $slotExists = collect($availableSlots)->contains(fn ($slot) => $slot['start_time'] === $startTime);

        if (! $slotExists) {
            throw new \Exception('این ساعت در برنامه وکیل موجود نیست یا قبلاً رزرو شده است');
        }

        $scheduledDateTime = $selectedDate->setTimeFromTimeString($startTime);

        $exists = Consultation::where('lawyer_id', $lawyer->id)
            ->where('scheduled_at', $scheduledDateTime)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->lockForUpdate()
            ->exists();

        if ($exists) {
            throw new \Exception('این ساعت هم‌اکنون توسط کاربر دیگری رزرو شد. لطفاً ساعت دیگری انتخاب کنید');
        }

        $appointmentPrice = Setting::where('key', 'pricing.appointment_price')->value('value') ?? 500000;

        $consultation = Consultation::create([
            'user_id' => $userId,
            'lawyer_id' => $lawyer->id,
            'type' => 'appointment',
            'title' => 'مشاوره حضوری با '.$lawyer->name,
            'price' => $appointmentPrice,
            'status' => 'pending',
            'scheduled_at' => $scheduledDateTime,
        ]);
        $lawyer->notify(new NewReservationNotification($consultation));

        $payment = Payment::create([
            'user_id' => $userId,
            'payable_type' => Consultation::class,
            'payable_id' => $consultation->id,
            'tracking_code' => Payment::generateTrackingCode(),
            'amount' => $appointmentPrice,
            'status' => 'pending',
            'gateway' => 'zarinpal',
            'description' => 'پرداخت مشاوره حضوری - '.$consultation->title,
        ]);

        $consultation->update(['payment_id' => $payment->id]);

        $zarinpal = new ZarinpalService;
        $result = $zarinpal->request(
            (float) $payment->amount,
            $payment->description,
            route('reserve.verify', $payment->id)
        );

        if (! $result['success']) {
            throw new \Exception($result['message'] ?? 'خطا در اتصال به درگاه پرداخت');
        }

        $payment->update(['authority' => $result['authority']]);

        $jalali = Jalalian::fromCarbon($selectedDate);
        Cache::forget("slots_{$lawyer->id}_{$selectedDate->format('Y-m-d')}");
        Cache::forget("booked_dates_{$lawyer->id}_{$jalali->getYear()}_{$jalali->getMonth()}");

        return redirect()->away($result['url']);
    }

    private function slotsCacheKey(Lawyer $lawyer, string $date): string
    {
        return "slots_{$lawyer->id}_{$lawyer->updated_at->timestamp}_{$date}";
    }
}
