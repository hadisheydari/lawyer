<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
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
}