<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\OtpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LawyerAuthController extends Controller
{
    // ─── ارسال OTP وکیل ───────────────────────────────────────────────────────
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ], [
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.regex'    => 'فرمت شماره موبایل صحیح نیست.',
        ]);

        $lawyerExists = Lawyer::where('phone', $request->phone)
            ->where('is_active', true)
            ->exists();

        if (!$lawyerExists) {
            return response()->json([
                'success' => false,
                'message' => 'شماره وارد شده در لیست وکلا یافت نشد یا غیرفعال است.',
                'errors'  => ['phone' => ['شماره وارد شده در لیست وکلا یافت نشد یا غیرفعال است.']],
            ], 422);
        }

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::updateOrCreate(
            ['phone' => $request->phone],
            ['code' => $code, 'expires_at' => now()->addMinutes(2), 'is_used' => false]
        );

        Log::info("📱 Lawyer OTP [{$code}] for {$request->phone}");

        return response()->json([
            'success' => true,
            'message' => 'کد تایید برای شما ارسال شد.',
        ]);
    }

    // ─── تأیید OTP وکیل و دریافت توکن ───────────────────────────────────────
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'code'  => ['required', 'digits:6'],
        ]);

        $otp = OtpCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'کد نامعتبر یا منقضی شده است.',
                'errors'  => ['code' => ['کد نامعتبر یا منقضی شده است.']],
            ], 422);
        }

        $otp->update(['is_used' => true]);

        $lawyer = Lawyer::where('phone', $request->phone)->firstOrFail();

        // revoke توکن‌های قدیمی
        $lawyer->tokens()->delete();

        $token = $lawyer->createToken('lawyer-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'ورود موفقیت‌آمیز بود.',
            'token'   => $token,
            'lawyer'  => [
                'id'               => $lawyer->id,
                'name'             => $lawyer->name,
                'phone'            => $lawyer->phone,
                'email'            => $lawyer->email,
                'license_number'   => $lawyer->license_number,
                'license_grade'    => $lawyer->license_grade,
                'experience_years' => $lawyer->experience_years,
                'specializations'  => $lawyer->specializations,
                'image_url'        => $lawyer->image_url,
                'available_for_chat'        => $lawyer->available_for_chat,
                'available_for_call'        => $lawyer->available_for_call,
                'available_for_appointment' => $lawyer->available_for_appointment,
            ],
        ]);
    }

    // ─── خروج وکیل ───────────────────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'با موفقیت خارج شدید.',
        ]);
    }

    // ─── اطلاعات وکیل جاری ───────────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        $lawyer = $request->user();

        return response()->json([
            'success' => true,
            'lawyer'  => [
                'id'               => $lawyer->id,
                'name'             => $lawyer->name,
                'slug'             => $lawyer->slug,
                'phone'            => $lawyer->phone,
                'email'            => $lawyer->email,
                'bio'              => $lawyer->bio,
                'education'        => $lawyer->education,
                'license_number'   => $lawyer->license_number,
                'license_grade'    => $lawyer->license_grade,
                'experience_years' => $lawyer->experience_years,
                'specializations'  => $lawyer->specializations,
                'image_url'        => $lawyer->image_url,
                'available_for_chat'        => $lawyer->available_for_chat,
                'available_for_call'        => $lawyer->available_for_call,
                'available_for_appointment' => $lawyer->available_for_appointment,
            ],
        ]);
    }
}





