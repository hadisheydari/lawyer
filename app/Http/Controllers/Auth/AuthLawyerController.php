<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // <-- این کلاس برای ارسال درخواست اضافه شد
use Illuminate\Support\Facades\Log;

class AuthLawyerController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('lawyer')->check()) {
            return redirect()->route('lawyer.dashboard');
        }
        return view('auth.lawyer-login');
    }

    // ارسال کد برای وکیل
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ]);

        // چک می‌کنیم که آیا اصلاً این شماره موبایل متعلق به یک وکیل هست؟
        $lawyerExists = Lawyer::where('phone', $request->phone)->where('is_active', true)->exists();
        if (!$lawyerExists) {
            return back()->withErrors(['phone' => 'شماره وارد شده در لیست وکلا یافت نشد یا غیرفعال است.']);
        }

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::updateOrCreate(
            ['phone' => $request->phone],
            ['code' => $code, 'expires_at' => now()->addMinutes(2), 'is_used' => false]
        );

        // لاگ کردن کد در حالت لوکال
        Log::info("📱 Lawyer OTP [{$code}] for {$request->phone}");

        // فراخوانی متد ارسال پیامک
        $this->sendSms($request->phone, $code);

        session(['lawyer_otp_phone' => $request->phone]);

        return back()->with('info', "کد تایید برای شما ارسال شد.");
    }

    // تایید کد و ورود وکیل
    public function verifyOtp(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $phone = session('lawyer_otp_phone');
        if (!$phone) return redirect()->route('lawyer.login');

        $otp = OtpCode::where('phone', $phone)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'کد نامعتبر یا منقضی شده است.']);
        }

        $otp->update(['is_used' => true]);
        $lawyer = Lawyer::where('phone', $phone)->first();

        Auth::guard('lawyer')->login($lawyer, true);
        session()->forget('lawyer_otp_phone');

        return redirect()->route('lawyer.dashboard');
    }

    public function logout()
    {
        Auth::guard('lawyer')->logout();
        return redirect()->route('lawyer.login');
    }

    // متد ارسال پیامک اضافه شده (مشابه کنترلر کاربران)
    protected function sendSms($phone, $code)
    {
        $username = config('services.melipayamak.username');
        $password = config('services.melipayamak.password');
        $bodyId = config('services.melipayamak.body_id');

        // اگر اطلاعات در کانفیگ وجود نداشت (مثل محیط لوکال)، کد فقط لاگ می‌شود
        if (empty($username) || empty($password) || empty($bodyId)) {
            Log::channel('single')->info("📱 OTP [{$code}] for {$phone}");

            return;
        }

        try {
            $response = Http::withoutVerifying()->post('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber', [
                'username' => $username,
                'password' => $password,
                'to' => $phone,
                'bodyId' => $bodyId,
                'text' => (string) $code,
            ]);

            $result = $response->json();

            // بررسی وضعیت موفقیت ارسال در ملی‌پیامک (RetStatus باید 1 باشد)
            if (! isset($result['RetStatus']) || $result['RetStatus'] != 1) {
                Log::error('Melipayamak SMS Error for phone '.$phone.':', $result ?? []);
            }

        } catch (\Exception $e) {
            // ثبت خطاهای ارتباطی (مثل تایم‌اوت شدن سرور پیامک)
            Log::error('Melipayamak Connection Exception: '.$e->getMessage());
        }
    }
}