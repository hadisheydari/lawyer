<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Lawyer;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;

class ReserveController extends Controller
{
    private const CACHE_TTL = 300; // 5 minutes

    public function index(Request $request)
    {
        $lawyerSlug = $request->query('lawyer');

        if ($lawyerSlug) {
            $lawyer = Lawyer::where('slug', $lawyerSlug)->firstOrFail();
        } else {
            $lawyer = Lawyer::first();
        }

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
                return response()->json([
                    'success' => false,
                    'message' => 'نمی‌توانید برای تاریخ گذشته نوبت بگیرید',
                ], 400);
            }

            if ($date->dayOfWeek === Carbon::FRIDAY) {
                return response()->json([
                    'success' => false,
                    'message' => 'روز جمعه تعطیل است',
                ], 400);
            }

            $cacheKey = "slots_{$lawyer->id}_{$date->format('Y-m-d')}";

            $slots = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($lawyer, $date) {
                return $lawyer->getAvailableSlots($date->format('Y-m-d'));
            });

            return response()->json([
                'success' => true,
                'slots' => $slots,
                'date' => $date->format('Y-m-d'),
            ]);

        } catch (\Exception $e) {
            Log::error('Get Available Slots Error', [
                'error' => $e->getMessage(),
                'date' => $request->date,
                'lawyer_id' => $request->lawyer_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت ساعات موجود',
            ], 500);
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
            return DB::transaction(function () use ($validated) {
                return $this->processReservation($validated, Auth::id());
            });
        } catch (\Exception $e) {
            Log::error('Reservation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'data' => $validated,
            ]);

            return back()->with('error', $e->getMessage())->withInput();
        }
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

        $bookedDates = $this->getBookedDates($lawyerId, $month, $year);

        return [
            'days_in_month' => $daysInMonth,
            'start_day_of_week' => $startDayOfWeek,
            'month' => $month,
            'year' => $year,
            'prev_month' => $prevMonth,
            'prev_year' => $prevYear,
            'next_month' => $nextMonth,
            'next_year' => $nextYear,
            'booked_dates' => $bookedDates,
        ];
    }

    private function getBookedDates(int $lawyerId, int $month, int $year): array
    {
        $cacheKey = "booked_dates_{$lawyerId}_{$year}_{$month}";

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

    /**
     * ثبت نوبت — بدون درگاه پرداخت.
     * نوبت با وضعیت pending ثبت می‌شود و وکیل باید بعداً از پنل خودش تأیید کند.
     * هماهنگی پرداخت از طریق تماس/واتساپ با دفتر انجام می‌شود.
     */
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

        Consultation::create([
            'user_id' => $userId,
            'lawyer_id' => $lawyer->id,
            'type' => 'appointment',
            'title' => 'مشاوره حضوری با '.$lawyer->name,
            'price' => $appointmentPrice,
            'status' => 'pending',
            'scheduled_at' => $scheduledDateTime,
        ]);

        $jalali = Jalalian::fromCarbon($selectedDate);
        Cache::forget("slots_{$lawyer->id}_{$selectedDate->format('Y-m-d')}");
        Cache::forget("booked_dates_{$lawyer->id}_{$jalali->getYear()}_{$jalali->getMonth()}");

        return redirect()->route('reserve.index', ['lawyer' => $lawyer->slug])
            ->with('success', 'درخواست نوبت شما ثبت شد. جهت هماهنگی نهایی و پرداخت، همکاران ما با شما تماس می‌گیرند یا می‌توانید از طریق تماس/واتساپ با دفتر هماهنگ کنید.');
    }
}