<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Models\Consultation;
use App\Models\Lawyer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ReserveController extends Controller
{
    private const CACHE_TTL = 300; // 5 minutes
    private const ZARINPAL_TIMEOUT = 10;
    
    public function index(Request $request)
    {
        $lawyerSlug = $request->query('lawyer');
        $lawyer = $lawyerSlug 
            ? Lawyer::where('slug', $lawyerSlug)->firstOrFail() 
            : Lawyer::first();

        if (!$lawyer) {
            return redirect()->route('home')->with('error', 'وکیلی یافت نشد');
        }

        $currentMonth = (int) $request->query('month', jdate()->getMonth());
        $currentYear = (int) $request->query('year', jdate()->getYear());

        $calendar = $this->generateCalendar($currentMonth, $currentYear, $lawyer->id);

        return view('reserve', compact('lawyer', 'calendar', 'currentMonth', 'currentYear'));
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

            // Check if date is in the past
            if ($date->isPast() && !$date->isToday()) {
                return response()->json([
                    'success' => false,
                    'message' => 'نمی‌توانید برای تاریخ گذشته نوبت بگیرید'
                ], 400);
            }

            // Check if it's Friday
            if ($date->dayOfWeek === Carbon::FRIDAY) {
                return response()->json([
                    'success' => false,
                    'message' => 'روز جمعه تعطیل است'
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
                'message' => 'خطا در دریافت ساعات موجود'
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

        if (!Auth::check()) {
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

    public function verifyPayment(Request $request, Payment $payment)
    {
        $authority = $request->query('Authority');
        $status = $request->query('Status');

        if ($status !== 'OK') {
            return $this->handleFailedPayment($payment, 'کاربر پرداخت را لغو کرد');
        }

        try {
            return DB::transaction(function () use ($payment, $authority) {
                return $this->verifyWithZarinpal($payment, $authority);
            });
        } catch (\Exception $e) {
            Log::error('Payment Verification Error', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'authority' => $authority,
            ]);

            return $this->handleFailedPayment($payment, 'خطا در تأیید پرداخت');
        }
    }

    // Private Helper Methods

    private function generateCalendar(int $month, int $year, int $lawyerId): array
    {
        $firstDay = jmktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = jdate('t', $firstDay);
        $startDayOfWeek = (int) jdate('w', $firstDay);

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
            $firstDay = jmktime(0, 0, 0, $month, 1, $year);
            $lastDay = jmktime(23, 59, 59, $month, jdate('t', $firstDay), $year);

            $startDate = jdate('Y-m-d', $firstDay);
            $endDate = jdate('Y-m-d', $lastDay);

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
        session([
            'pending_reservation' => $data,
            'intended_url' => route('reserve.index', ['lawyer' => request()->query('lawyer')]),
        ]);

        return redirect()->route('login')
            ->with('info', 'برای ثبت نوبت، لطفاً ابتدا وارد شوید یا ثبت‌نام کنید');
    }

    private function processReservation(array $data, int $userId)
    {
        $lawyer = Lawyer::lockForUpdate()->findOrFail($data['lawyer_id']);
        $selectedDate = Carbon::parse($data['selected_date']);
        $startTime = $data['selected_time'];

        // Validate Friday
        if ($selectedDate->dayOfWeek === Carbon::FRIDAY) {
            throw new \Exception('روز جمعه تعطیل است');
        }

        // Validate slot exists in schedule
        $availableSlots = $lawyer->getAvailableSlots($selectedDate->format('Y-m-d'));
        $slotExists = collect($availableSlots)->contains(fn($slot) => $slot['start_time'] === $startTime);

        if (!$slotExists) {
            throw new \Exception('این ساعت در برنامه وکیل موجود نیست یا قبلاً رزرو شده است');
        }

        $scheduledDateTime = $selectedDate->setTimeFromTimeString($startTime);

        // Check double booking with lock
        $exists = Consultation::where('lawyer_id', $lawyer->id)
            ->where('scheduled_at', $scheduledDateTime)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->lockForUpdate()
            ->exists();

        if ($exists) {
            throw new \Exception('این ساعت هم‌اکنون توسط کاربر دیگری رزرو شد. لطفاً ساعت دیگری انتخاب کنید');
        }

        // Create consultation
        $consultation = Consultation::create([
            'user_id' => $userId,
            'lawyer_id' => $lawyer->id,
            'type' => 'appointment',
            'title' => 'مشاوره حضوری با ' . $lawyer->name,
            'price' => $lawyer->consultation_price ?? 500000,
            'status' => 'pending',
            'scheduled_at' => $scheduledDateTime,
        ]);

        // Create payment
        $payment = $this->createPayment($userId, $consultation);

        // Request Zarinpal
        $authority = $this->requestZarinpalPayment($payment, $lawyer);

        $payment->update(['authority' => $authority]);

        // Clear cache
        Cache::forget("slots_{$lawyer->id}_{$selectedDate->format('Y-m-d')}");
        Cache::forget("booked_dates_{$lawyer->id}_{$selectedDate->month}_{$selectedDate->year}");

        return redirect($this->getZarinpalStartUrl($authority));
    }

    private function createPayment(int $userId, Consultation $consultation): Payment
    {
        return Payment::create([
            'user_id' => $userId,
            'payable_type' => Consultation::class,
            'payable_id' => $consultation->id,
            'tracking_code' => 'LAW-' . strtoupper(uniqid()),
            'amount' => $consultation->price,
            'status' => 'pending',
            'gateway' => 'zarinpal',
        ]);
    }

    private function requestZarinpalPayment(Payment $payment, Lawyer $lawyer): string
    {
        $merchantId = config('services.zarinpal.merchant_id');
        $isSandbox = config('services.zarinpal.sandbox', true);
        $baseUrl = $isSandbox 
            ? 'https://sandbox.zarinpal.com/pg/v4/payment' 
            : 'https://api.zarinpal.com/pg/v4/payment';

        $response = Http::timeout(self::ZARINPAL_TIMEOUT)
            ->post("{$baseUrl}/request.json", [
                'merchant_id' => $merchantId,
                'amount' => $payment->amount * 10,
                'description' => "رزرو مشاوره با {$lawyer->name} - {$payment->tracking_code}",
                'callback_url' => route('reserve.verify', $payment->id),
                'mobile' => Auth::user()->phone ?? '',
                'email' => Auth::user()->email ?? '',
            ]);

        if ($response->failed()) {
            throw new \Exception('خطا در اتصال به درگاه پرداخت. لطفاً دوباره تلاش کنید');
        }

        $result = $response->json();

        if (!isset($result['data']['code']) || $result['data']['code'] != 100) {
            throw new \Exception('درگاه پرداخت پاسخ نامعتبر داد');
        }

        return $result['data']['authority'];
    }

    private function getZarinpalStartUrl(string $authority): string
    {
        $isSandbox = config('services.zarinpal.sandbox', true);
        $baseUrl = $isSandbox 
            ? 'https://sandbox.zarinpal.com/pg/StartPay/' 
            : 'https://www.zarinpal.com/pg/StartPay/';

        return $baseUrl . $authority;
    }

    private function verifyWithZarinpal(Payment $payment, string $authority)
    {
        $merchantId = config('services.zarinpal.merchant_id');
        $isSandbox = config('services.zarinpal.sandbox', true);
        $baseUrl = $isSandbox 
            ? 'https://sandbox.zarinpal.com/pg/v4/payment' 
            : 'https://api.zarinpal.com/pg/v4/payment';

        $response = Http::timeout(self::ZARINPAL_TIMEOUT)
            ->post("{$baseUrl}/verify.json", [
                'merchant_id' => $merchantId,
                'authority' => $authority,
                'amount' => $payment->amount * 10,
            ]);

        if ($response->failed()) {
            throw new \Exception('خطا در تأیید پرداخت');
        }

        $result = $response->json();

        if (!isset($result['data']['code']) || $result['data']['code'] != 100) {
            throw new \Exception('پرداخت تأیید نشد');
        }

        $payment->update([
            'status' => 'paid',
            'ref_id' => $result['data']['ref_id'] ?? null,
            'paid_at' => now(),
        ]);

        $payment->payable->update(['status' => 'confirmed']);

        return redirect()->route('dashboard.index')
            ->with('success', 'پرداخت با موفقیت انجام شد. نوبت شما ثبت گردید');
    }

    private function handleFailedPayment(Payment $payment, string $message)
    {
        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'failed']);
            $payment->payable->update(['status' => 'cancelled']);
        });

        return redirect()->route('dashboard.index')->with('error', $message);
    }
}
