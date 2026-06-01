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

        // ─── فقط کاربران ثبت‌شده می‌توانند از این مسیر وارد شوند ────────────
        if (! User::where('phone', $phone)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'این شماره در سیستم ثبت نشده است. لطفاً ابتدا ثبت‌نام کنید.']);
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

        $isRegisterFlow = session('otp_for_register', false);
        $registerData   = session('register_data');

        if ($isRegisterFlow) {
            // ─── جریان ثبت‌نام: ساخت کاربر جدید ─────────────────────────────
            // بررسی مضاعف برای جلوگیری از race condition
            if (User::where('phone', $phone)->exists()) {
                session()->forget(['otp_phone', 'otp_for_register', 'register_data']);
                return redirect()->route('login')
                    ->with('info', 'این شماره قبلاً ثبت شده است. لطفاً وارد شوید.');
            }

            $user = User::create([
                'phone'         => $phone,
                'name'          => $registerData['name'] ?? ('کاربر ' . substr($phone, -4)),
                'email'         => $registerData['email'] ?? null,
                'national_code' => $registerData['national_code'] ?? null,
                'user_type'     => 'simple',
                'status'        => 'active',
            ]);
        } else {
            // ─── جریان لاگین: فقط کاربر موجود ────────────────────────────────
            $user = User::where('phone', $phone)->first();

            if (! $user) {
                return redirect()->route('login')
                    ->withErrors(['phone' => 'این شماره در سیستم ثبت نشده است. لطفاً ابتدا ثبت‌نام کنید.']);
            }
        }

        if ($user->isBlocked()) {
            return redirect()->route('login')
                ->withErrors(['phone' => 'حساب کاربری شما مسدود شده است.']);
        }

        $request->session()->regenerate();
        Auth::login($user, remember: true);
        session()->forget(['otp_phone', 'otp_for_register', 'register_data']);

        // اگر نوبت پندینگ داشت، برگرد به رزرو
        if (session()->has('pending_reservation')) {
            $pending = session()->pull('pending_reservation');

            return redirect()->route('reserve.index', [
                'lawyer' => optional(\App\Models\Lawyer::find($pending['lawyer_id']))->slug,
            ])->with('info', 'وارد شدید. لطفاً نوبت خود را تکمیل کنید.');
        }

        return redirect()->route('dashboard.index');
    }

    // ─── نمایش صفحه ثبت نام ───────────────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ─── ثبت نام ──────────────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => ['required', 'string', 'max:50'],
            'last_name'     => ['required', 'string', 'max:50'],
            'phone'         => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,phone'],
            'national_code' => ['nullable', 'digits:10', 'unique:users,national_code'],
            'email'         => ['nullable', 'email', 'unique:users,email'],
        ], [
            'first_name.required'    => 'نام الزامی است.',
            'last_name.required'     => 'نام خانوادگی الزامی است.',
            'phone.required'         => 'شماره موبایل الزامی است.',
            'phone.regex'            => 'فرمت شماره موبایل صحیح نیست.',
            'phone.unique'           => 'این شماره قبلاً ثبت شده است. لطفاً وارد شوید.',
            'national_code.digits'   => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique'   => 'این کد ملی قبلاً ثبت شده است.',
            'email.email'            => 'فرمت ایمیل صحیح نیست.',
            'email.unique'           => 'این ایمیل قبلاً ثبت شده است.',
        ]);

        session([
            'register_data' => [
                'name'          => $request->first_name . ' ' . $request->last_name,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'national_code' => $request->national_code,
            ],
        ]);

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('phone', $request->phone)->delete();
        OtpCode::create([
            'phone'      => $request->phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes(2),
            'is_used'    => false,
        ]);

        $this->sendSms($request->phone, $code);
        session(['otp_phone' => $request->phone, 'otp_for_register' => true]);

        return redirect()->route('login')
            ->with('info', 'کد تأیید ارسال شد. لطفاً کد را وارد کنید.');
    }

    // ─── پاک کردن session OTP (تغییر شماره) ──────────────────────────────────
    public function clearOtpSession(Request $request)
    {
        session()->forget(['otp_phone', 'otp_for_register', 'register_data']);

        return redirect()->route('login');
    }

    // ─── خروج ─────────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─── ارسال SMS با SMS.ir ──────────────────────────────────────────────────
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