<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class ReserveController extends Controller
{
    public function index(Request $request)
    {
        // ۱. دریافت وکیل (اگر کاربر از صفحه پروفایل وکیل خاصی آمده باشد)
        $lawyerSlug = $request->query('lawyer');
        $selectedLawyer = null;
        if ($lawyerSlug) {
            $selectedLawyer = Lawyer::where('slug', $lawyerSlug)->first();
        }

        // ۲. تنظیمات تقویم جلالی
        $now = Jalalian::now();

        // اگر کاربر ماه خاصی رو درخواست نکرده بود، ماه و سال فعلی رو در نظر بگیر
        $year = (int) $request->query('year', $now->getYear());
        $month = (int) $request->query('month', $now->getMonth());

        // ساخت یک آبجکت برای روز اول این ماه
        $firstDayOfMonth = new Jalalian($year, $month, 1);

        // تعداد روزهای این ماه (۲۹، ۳۰ یا ۳۱)
        $daysInMonth = $firstDayOfMonth->getMonthDays();

        // نام ماه (مثلاً: بهمن)
        $monthName = $firstDayOfMonth->format('%B');

        // ۳. پیدا کردن روز اول هفته برای ساخت خانه‌های خالی
        // در پکیج جلالی: شنبه = 0، یکشنبه = 1، ... جمعه = 6
        $firstDayOfWeek = $firstDayOfMonth->format('w');

        // ۴. متغیرهای روز جاری برای غیرفعال کردن روزهای گذشته
        $nowYear = $now->getYear();
        $nowMonth = $now->getMonth();
        $nowDay = $now->getDay();

        // محاسبه ماه قبل و بعد برای دکمه‌های تقویم
        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;

        return view('public.reserve', compact(
            'selectedLawyer',
            'year', 'month', 'monthName', 'daysInMonth', 'firstDayOfWeek',
            'nowYear', 'nowMonth', 'nowDay',
            'prevMonth', 'prevYear', 'nextMonth', 'nextYear'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_date' => 'required|date',
            'lawyer_id' => 'nullable|exists:lawyers,id',
        ]);

        $lawyerId = $request->lawyer_id ?? \App\Models\Lawyer::first()->id ?? 1;
        $userId = Auth::id();

        if (! $userId) {
            session(['pending_reservation_date' => $request->selected_date]);
            session(['pending_lawyer_id' => $lawyerId]);

            return redirect()->route('login')->with('info', 'برای ثبت نوبت، لطفاً ابتدا وارد شوید.');
        }

        // ۱. ثبت در دیتابیس
        $consultation = Consultation::create([
            'user_id' => $userId,
            'lawyer_id' => $lawyerId,
            'type' => 'appointment',
            'title' => 'درخواست مشاوره حضوری',
            'price' => 50000, // مبلغ تست: ۵۰ هزار تومان
            'status' => 'pending',
            'scheduled_at' => $request->selected_date.' 17:00:00',
        ]);

        $trackingCode = 'LAW-'.strtoupper(uniqid());

        $payment = Payment::create([
            'user_id' => $userId,
            'payable_type' => Consultation::class,
            'payable_id' => $consultation->id,
            'tracking_code' => $trackingCode,
            'amount' => $consultation->price,
            'status' => 'pending',
            'gateway' => 'zarinpal',
        ]);

        // ۲. ارسال درخواست به زرین‌پال
        $merchantId = env('ZARINPAL_MERCHANT_ID', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
        $isSandbox = env('ZARINPAL_SANDBOX', true);

        $baseUrl = $isSandbox ? 'https://sandbox.zarinpal.com/pg/v4/payment' : 'https://api.zarinpal.com/pg/v4/payment';

        // زرین پال مبالغ رو به ریال می‌گیره، پس ضرب در ۱۰ می‌کنیم
        $response = Http::post("{$baseUrl}/request.json", [
            'merchant_id' => $merchantId,
            'amount' => $payment->amount * 10,
            'description' => 'رزرو نوبت مشاوره حقوقی - '.$trackingCode,
            'callback_url' => route('reserve.verify', $payment->id),
            'mobile' => Auth::user()->phone, // اختیاری: برای ذخیره شماره کاربر در درگاه
        ]);

        $result = $response->json();

        // ۳. بررسی جواب بانک و انتقال کاربر
        if (isset($result['data']['code']) && $result['data']['code'] == 100) {
            $authority = $result['data']['authority'];
            $payment->update(['authority' => $authority]);

            $startPayUrl = $isSandbox ? 'https://sandbox.zarinpal.com/pg/StartPay/' : 'https://www.zarinpal.com/pg/StartPay/';

            // هدایت مستقیم کاربر به صفحه پرداخت بانک
            return redirect($startPayUrl.$authority);
        }

        return back()->with('error', 'خطا در اتصال به درگاه بانکی.');
    }

    // ─── متد جدید: برگشت کاربر از بانک ───────────────────────────────────────
    public function verifyPayment(Request $request, Payment $payment)
    {
        $authority = $request->query('Authority');
        $status = $request->query('Status');

        // اگر کاربر درگاه رو بست یا انصراف داد
        if ($status !== 'OK') {
            $payment->update(['status' => 'failed']);

            return redirect()->route('dashboard.index')->with('error', 'پرداخت لغو شد یا ناموفق بود.');
        }

        $merchantId = env('ZARINPAL_MERCHANT_ID', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
        $isSandbox = env('ZARINPAL_SANDBOX', true);
        $baseUrl = $isSandbox ? 'https://sandbox.zarinpal.com/pg/v4/payment' : 'https://api.zarinpal.com/pg/v4/payment';

        // تایید نهایی پرداخت با بانک (Verify)
        $response = Http::post("{$baseUrl}/verify.json", [
            'merchant_id' => $merchantId,
            'amount' => $payment->amount * 10,
            'authority' => $authority,
        ]);

        $result = $response->json();

        // کد ۱۰۰ یعنی پرداخت موفق بود، کد ۱۰۱ یعنی قبلاً تایید شده
        if (isset($result['data']['code']) && in_array($result['data']['code'], [100, 101])) {

            $refId = $result['data']['ref_id']; // شماره تراکنش بانکی

            // آپدیت جدول پرداخت
            $payment->update([
                'status' => 'paid',
                'ref_id' => $refId,
                'paid_at' => now(),
            ]);

            // آپدیت جدول مشاوره به حالت تایید شده
            $consultation = $payment->payable;
            $consultation->update(['status' => 'confirmed']);

            // **اینجا می‌تونیم در قدم بعدی کدهای پیامک کاوه‌نگار رو اضافه کنیم**

            return redirect()->route('dashboard.index')
                ->with('success', "پرداخت شما با موفقیت انجام شد. کد رهگیری بانکی: {$refId}");
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('dashboard.index')->with('error', 'خطا در تایید نهایی پرداخت از سمت بانک.');
    }
}
