<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function generate(User $user): UserOtp
    {
        $code = rand(100000, 999999); // کد ۶ رقمی
        $expiresAt = now()->addMinutes(2); // اعتبار دو دقیقه

        $otp = UserOtp::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

//        $this->sendSms($user->phone, $code);

        return $otp;
    }

    protected function sendSms(string $phone, string $code): void
    {
        $apiKey = config('services.kavenegar.key');
        $sender = "10008663"; // شماره فرستنده
        $message = "کد ورود شما: $code";

        Http::get("https://api.kavenegar.com/v1/{$apiKey}/sms/send.json", [
            'receptor' => $phone,
            'sender' => $sender,
            'message' => $message
        ]);
    }

    public function verify(User $user, string $code): bool
    {
        $otp = UserOtp::where('user_id', $user->id)
            ->where('code', $code)
            ->first();

        if (!$otp || $otp->isExpired()) {
            return false;
        }

        $otp->delete(); // مصرف یک‌بار
        return true;
    }
}
