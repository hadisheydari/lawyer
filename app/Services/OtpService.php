<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use App\Models\Partition;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.kavenegar.api_key');
    }

    /**
     * تولید OTP برای کاربر
     */
    public function generate(User $user, int $minutes = 2): ?UserOtp
    {
        if (!$user->phone) {
            Log::warning("کاربر {$user->id} شماره موبایل ندارد، پیامک ارسال نشد.");
            return null;
        }

        $code = rand(100000, 999999);
        $expiresAt = now()->addMinutes($minutes);

        $otp = UserOtp::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => $expiresAt]
        );
        $template = 'Shahabhaml';
        $text = ' تست';
        $this->sendSms($text, $user->phone, $code , $template);

        return $otp;
    }

    /**
     * تولید OTP برای پارتیشن و صاحب کالا
     */
    public function deliverCode(Partition $partition, int $minutes = 5): ?UserOtp
    {
        $phone = $partition->cargo?->owner?->phone;

        if (!$phone) {
            Log::warning("پارتیشن {$partition->id} صاحب کالا شماره موبایل ندارد.");
            return null;
        }

        // تولید کد OTP امن
        $code = random_int(100000, 999999);
        $expiresAt = now()->addMinutes($minutes);

        // بررسی اگر OTP قبلی هنوز معتبر است
        $otp = UserOtp::updateOrCreate(
            ['partition_id' => $partition->id],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        // پیام واقعی برای ارسال SMS
        $template = 'ShahabReciveVerify';
        $text = "تست";
        $this->sendSms($text, $phone, $code, $template);

        return $otp;
    }

    /**
     * ارسال پیامک از طریق کاوه نگار
     */
    protected function sendSms(string $text, string $phone, string $code ,string $template): void
    {
        if (!$this->apiKey) {
            Log::error("API Key کاوه نگار تعریف نشده است.");
            return;
        }

        $postFields = [
            "receptor" => $phone,
            "token"    => $code,
            "template" => $template,
            "token20"  => $text
        ];

        $ch = curl_init("https://api.kavenegar.com/v1/{$this->apiKey}/verify/lookup.json");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error("خطا در ارسال پیامک به {$phone}: {$error}");
            return;
        }

        $resData = json_decode($response, true);
        $status = $resData['return']['status'] ?? null;
        $message = $resData['return']['message'] ?? 'نامشخص';

        if ($status !== 200) {
            Log::warning("پیامک به {$phone} ارسال نشد: {$message}");
        } else {
            Log::info("پیامک به {$phone} با موفقیت ارسال شد. کد: {$code}");
        }
    }

    /**
     * بررسی اعتبار کد OTP
     */
    public function verify(User $user, string $code): bool
    {
        $otp = UserOtp::where('user_id', $user->id)
            ->where('code', $code)
            ->first();

        return $otp && !$otp->isExpired();
    }

    public function verifyPartition(Partition $partition, string $code): bool
    {
        $otp = UserOtp::where('partition_id', $partition->id)
            ->where('code', $code)
            ->first();

        return $otp && !$otp->isExpired();
    }
}
