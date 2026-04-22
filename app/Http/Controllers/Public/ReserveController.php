<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Consultation;
use App\Models\Payment;
use Carbon\Carbon;

class ReserveController extends Controller
{
    public function index(Request $request)
    {
        $lawyerSlug = $request->query('lawyer');
        $selectedLawyer = null;
        if ($lawyerSlug) {
            $selectedLawyer = Lawyer::where('slug', $lawyerSlug)->first();
        }

        $now = Jalalian::now();
        $year = (int) $request->query('year', $now->getYear());
        $month = (int) $request->query('month', $now->getMonth());

        $firstDayOfMonth = new Jalalian($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->getMonthDays();
        $monthName = $firstDayOfMonth->format('%B');
        $firstDayOfWeek = $firstDayOfMonth->format('w');

        $nowYear = $now->getYear();
        $nowMonth = $now->getMonth();
        $nowDay = $now->getDay();

        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;

        // روزهای رزرو شده
        $bookedDates = [];
        if ($selectedLawyer) {
            $bookedDates = Consultation::where('lawyer_id', $selectedLawyer->id)
                ->whereYear('scheduled_at', $year)
                ->whereMonth('scheduled_at', $month)
                ->where('status', '!=', 'cancelled')
                ->pluck('scheduled_at')
                ->map(fn($date) => Carbon::parse($date)->day)
                ->unique()
                ->toArray();
        }

        return view('public.reserve', compact(
            'selectedLawyer',
            'year', 'month', 'monthName', 'daysInMonth', 'firstDayOfWeek',
            'nowYear', 'nowMonth', 'nowDay',
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear',
            'bookedDates'
        ));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'lawyer_id' => 'required|exists:lawyers,id',
        ]);

        $lawyer = Lawyer::findOrFail($request->lawyer_id);
        $slots = $lawyer->getAvailableSlots($request->date);

        return response()->json([
            'slots' => collect($slots)->map(function ($slot, $index) {
                return [
                    'id' => $index,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'display' => substr($slot['start_time'], 0, 5) . ' - ' . substr($slot['end_time'], 0, 5),
                ];
            })->values()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'selected_time' => 'required',
            'lawyer_id' => 'nullable|exists:lawyers,id',
        ]);

        $lawyerId = $request->lawyer_id ?? Lawyer::first()->id ?? 1;
        $userId = Auth::id();

        if (!$userId) {
            session([
                'pending_reservation_date' => $request->selected_date,
                'pending_selected_time' => $request->selected_time,
                'pending_lawyer_id' => $lawyerId,
            ]);
            return redirect()->route('login')->with('info', 'برای ثبت نوبت، لطفاً ابتدا وارد شوید.');
        }

        $selectedDate = Carbon::parse($request->selected_date);
        
        // چک جمعه
        if ($selectedDate->dayOfWeek === 5) {
            return back()->withErrors(['selected_date' => 'جمعه‌ها تعطیل است']);
        }

        try {
            return DB::transaction(function () use ($request, $lawyerId, $userId, $selectedDate) {
                
                $startTime = $request->selected_time;
                $scheduledDateTime = $selectedDate->setTimeFromTimeString($startTime);

                // چک رزرو تکراری
                $exists = Consultation::where('lawyer_id', $lawyerId)
                    ->where('scheduled_at', $scheduledDateTime)
                    ->where('status', '!=', 'cancelled')
                    ->lockForUpdate()
                    ->exists();
                    
                if ($exists) {
                    return back()->withErrors(['selected_time' => 'این ساعت قبلاً رزرو شده است']);
                }

                $lawyer = Lawyer::find($lawyerId);
                
                $consultation = Consultation::create([
                    'user_id' => $userId,
                    'lawyer_id' => $lawyerId,
                    'type' => 'appointment',
                    'title' => 'درخواست مشاوره حضوری',
                    'price' => $lawyer->consultation_price ?? 50000,
                    'status' => 'pending',
                    'scheduled_at' => $scheduledDateTime,
                ]);

                $trackingCode = 'LAW-' . strtoupper(uniqid());

                $payment = Payment::create([
                    'user_id' => $userId,
                    'payable_type' => Consultation::class,
                    'payable_id' => $consultation->id,
                    'tracking_code' => $trackingCode,
                    'amount' => $consultation->price,
                    'status' => 'pending',
                    'gateway' => 'zarinpal',
                ]);

                $merchantId = env('ZARINPAL_MERCHANT_ID');
                $isSandbox = env('ZARINPAL_SANDBOX', true);
                $baseUrl = $isSandbox 
                    ? 'https://sandbox.zarinpal.com/pg/v4/payment' 
                    : 'https://api.zarinpal.com/pg/v4/payment';

                $response = Http::timeout(10)->post("{$baseUrl}/request.json", [
                    'merchant_id' => $merchantId,
                    'amount' => $payment->amount * 10,
                    'description' => 'رزرو نوبت مشاوره حقوقی - ' . $trackingCode,
                    'callback_url' => route('reserve.verify', $payment->id),
                    'mobile' => Auth::user()->phone ?? '',
                ]);

                if ($response->failed()) {
                    throw new \Exception('خطا در اتصال به درگاه');
                }

                $result = $response->json();

                if (isset($result['data']['code']) && $result['data']['code'] == 100) {
                    $authority = $result['data']['authority'];
                    $payment->update(['authority' => $authority]);

                    $startPayUrl = $isSandbox 
                        ? 'https://sandbox.zarinpal.com/pg/StartPay/' 
                        : 'https://www.zarinpal.com/pg/StartPay/';

                    return redirect($startPayUrl . $authority);
                }

                throw new \Exception('پاسخ نامعتبر از درگاه');
            });
            
        } catch (\Exception $e) {
            \Log::error('Reservation Error: ' . $e->getMessage());
            return back()->with('error', 'خطا در ثبت رزرو. لطفاً دوباره تلاش کنید.');
        }
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $authority = $request->query('Authority');
        $status = $request->query('Status');

        if ($status !== 'OK') {
            $payment->update(['status' => 'failed']);
            return redirect()->route('dashboard.index')->with('error', 'پرداخت لغو شد یا ناموفق بود.');
        }

        $merchantId = env('ZARINPAL_MERCHANT_ID');
        $isSandbox = env('ZARINPAL_SANDBOX', true);
        $baseUrl = $isSandbox 
            ? 'https://sandbox.zarinpal.com/pg/v4/payment' 
            : 'https://api.zarinpal.com/pg/v4/payment';

        $response = Http::post("{$baseUrl}/verify.json", [
            'merchant_id' => $merchantId,
            'amount' => $payment->amount * 10,
            'authority' => $authority,
        ]);

        $result = $response->json();

        if (isset($result['data']['code']) && in_array($result['data']['code'], [100, 101])) {
            $refId = $result['data']['ref_id'];

            $payment->update([
                'status' => 'paid',
                'ref_id' => $refId,
                'paid_at' => now(),
            ]);

            $consultation = $payment->payable;
            $consultation->update(['status' => 'confirmed']);

            return redirect()->route('dashboard.index')
                ->with('success', "پرداخت شما با موفقیت انجام شد. کد رهگیری بانکی: {$refId}");
        }

        $payment->update(['status' => 'failed']);
        return redirect()->route('dashboard.index')->with('error', 'خطا در تایید نهایی پرداخت از سمت بانک.');
    }
}
