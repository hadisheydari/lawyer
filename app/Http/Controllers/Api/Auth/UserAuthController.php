<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserAuthController extends Controller
{
    // ─── ارسال OTP ────────────────────────────────────────────────────────────
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ], [
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.regex' => 'فرمت شماره موبایل صحیح نیست.',
        ]);

        $phone = $request->phone;

        OtpCode::where('phone', $phone)->delete();

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
            'is_used' => false,
        ]);

        $this->sendSms($phone, $code);

        return response()->json([
            'success' => true,
            'message' => "کد تأیید به {$phone} ارسال شد.",
        ]);
    }

    // ─── ثبت نام ──────────────────────────────────────────────────────────────
    public function register(Request $request): JsonResponse
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

        // ذخیره اطلاعات ثبت نام به صورت موقت در cache
        $registerKey = 'register_data_'.$request->phone;
        cache()->put($registerKey, [
            'name' => $request->first_name.' '.$request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'national_code' => $request->national_code,
        ], now()->addMinutes(10));

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::where('phone', $request->phone)->delete();
        OtpCode::create([
            'phone' => $request->phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
            'is_used' => false,
        ]);

        $this->sendSms($request->phone, $code);

        return response()->json([
            'success' => true,
            'message' => 'کد تأیید ارسال شد. لطفاً کد را وارد کنید.',
            'phone' => $request->phone,
        ], 201);
    }

    // ─── تأیید OTP و دریافت توکن ──────────────────────────────────────────────
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'code' => ['required', 'digits:6'],
        ], [
            'phone.required' => 'شماره موبایل الزامی است.',
            'code.required' => 'کد تأیید الزامی است.',
            'code.digits' => 'کد تأیید باید ۶ رقم باشد.',
        ]);

        $otp = OtpCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            return response()->json([
                'success' => false,
                'message' => 'کد وارد شده نامعتبر یا منقضی شده است.',
                'errors' => ['code' => ['کد وارد شده نامعتبر یا منقضی شده است.']],
            ], 422);
        }

        $otp->update(['is_used' => true]);

        // بررسی اطلاعات ثبت‌نام از cache
        $registerKey = 'register_data_'.$request->phone;
        $registerData = cache()->get($registerKey);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $registerData['name'] ?? ('کاربر '.substr($request->phone, -4)),
                'email' => $registerData['email'] ?? null,
                'national_code' => $registerData['national_code'] ?? null,
                'user_type' => 'simple',
                'status' => 'active',
            ]
        );

        if ($registerData) {
            cache()->forget($registerKey);
        }

        if ($user->isBlocked()) {
            return response()->json([
                'success' => false,
                'message' => 'حساب کاربری شما مسدود شده است.',
            ], 403);
        }

        // revoke توکن‌های قدیمی
        $user->tokens()->delete();

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'ورود موفقیت‌آمیز بود.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'status' => $user->status,
            ],
        ]);
    }

    // ─── خروج ─────────────────────────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'با موفقیت خارج شدید.',
        ]);
    }

    // ─── اطلاعات کاربر جاری ──────────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'national_code' => $user->national_code,
                'user_type' => $user->user_type,
                'status' => $user->status,
                'upgraded_at' => $user->upgraded_at,
            ],
        ]);
    }

    // ─── ارسال SMS ────────────────────────────────────────────────────────────
    private function sendSms(string $phone, string $code): void
    {
        $username = config('services.melipayamak.username');
        $password = config('services.melipayamak.password');
        $bodyId = config('services.melipayamak.body_id');

        if (! $username || ! $password) {
            Log::channel('single')->info("📱 OTP [{$code}] for {$phone}");

            return;
        }

        try {
            $response = Http::timeout(10)->post('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber', [
                'username' => $username,
                'password' => $password,
                'text' => $code, // اگر پترن شما چند متغیر دارد، آن‌ها را با نقطه ویرگول (;) جدا کنید
                'to' => $phone,
                'bodyId' => (int) $bodyId,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['RetStatus']) && $result['RetStatus'] == 1) {
                Log::info('Melipayamak SMS Sent Successfully: '.$response->body());
            } else {
                Log::error('Melipayamak Error: '.$response->body());
            }
        } catch (\Exception $e) {
            Log::error("Melipayamak send failed for {$phone}: ".$e->getMessage());
        }
    }
}
