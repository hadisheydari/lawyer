<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ─── نمایش صفحه لاگین ─────────────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── ارسال کد OTP ─────────────────────────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ], [
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.regex' => 'فرمت شماره موبایل صحیح نیست.',
        ]);

        $phone = $request->phone;

        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'شما حساب کاربری ندارید لطفا ثبت نام کنید ']);

        }

        OtpCode::where('phone', $phone)->delete();

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
            'is_used' => false,
        ]);

        $this->sendSms($phone, $code);

        session(['otp_phone' => $phone]);

        return redirect()->route('login')
            ->with('info', "کد تأیید به {$phone} ارسال شد.");
    }

    // ─── تأیید OTP و ورود ─────────────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'کد تأیید الزامی است.',
            'code.digits' => 'کد تأیید باید ۶ رقم باشد.',
        ]);

        $phone = session('otp_phone');

        if (! $phone) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'لطفاً ابتدا شماره موبایل خود را وارد کنید.']);
        }

        $otp = OtpCode::where('phone', $phone)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            throw ValidationException::withMessages([
                'code' => 'کد وارد شده نامعتبر یا منقضی شده است.',
            ]);
        }

        $otp->update(['is_used' => true]);

        // اگر در جریان ثبت‌نام بودیم، اطلاعات رو از session بگیر
        $registerData = session('register_data');

        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => $registerData['name'] ?? ('کاربر '.substr($phone, -4)),
                'email' => $registerData['email'] ?? null,
                'national_code' => $registerData['national_code'] ?? null,
                'user_type' => 'simple',
                'status' => 'active',
            ]
        );

        if ($user->isBlocked()) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'حساب کاربری شما مسدود شده است.']);
        }

        Auth::login($user, remember: true);
        session()->forget(['otp_phone', 'otp_for_register', 'register_data']);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,phone'],
            'national_code' => ['nullable', 'digits:10', 'unique:users,national_code'],
            'email' => ['nullable', 'email', 'unique:users,email'],
        ], [
            'first_name.required' => 'نام الزامی است.',
            'last_name.required' => 'نام خانوادگی الزامی است.',
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.regex' => 'فرمت شماره موبایل صحیح نیست.',
            'phone.unique' => 'این شماره قبلاً ثبت شده. وارد شوید.',
            'national_code.digits' => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
        ]);

        session([
            'register_data' => [
                'name' => $request->first_name.' '.$request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'national_code' => $request->national_code,
            ],
        ]);

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('phone', $request->phone)->delete();
        OtpCode::create([
            'phone' => $request->phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
            'is_used' => false,
        ]);

        $this->sendSms($request->phone, $code);
        session(['otp_phone' => $request->phone, 'otp_for_register' => true]);

        return redirect()->route('login')
            ->with('info', 'کد تأیید ارسال شد. لطفاً کد را وارد کنید.');
    }

    // ─── خروج ─────────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─── ارسال SMS ────────────────────────────────────────────────────────────
    // اگر API key نباشد، فقط در لاگ ثبت می‌کند — کرش نمی‌دهد
    private function sendSms(string $phone, string $code): void
    {
        $apiKey = config('services.kavenegar.api_key');

        try {
            $response = Http::timeout(10)->get("https://api.kavenegar.com/v1/{$apiKey}/sms/send.json", [
                'receptor' => $phone,
                'sender' => '0018018949161',
                'message' => "کد ورود شما: {$code}",
            ]);

            Log::info($response->body());

        } catch (\Exception $e) {
            Log::error("SMS sending failed for {$phone}: ".$e->getMessage());
        }
    }
}
