<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZarinpalService
{
    private string $merchantId;
    private bool $sandbox;

    public function __construct()
    {
        $this->merchantId = (string) config('services.zarinpal.merchant_id');
        $this->sandbox    = (bool) config('services.zarinpal.sandbox', true);
    }

    private function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox.zarinpal.com/pg/rest/WebGate/'
            : 'https://api.zarinpal.com/pg/v4/payment/';
    }

    private function startPayUrl(string $authority): string
    {
        return $this->sandbox
            ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
            : "https://www.zarinpal.com/pg/StartPay/{$authority}";
    }

    /**
     * درخواست پرداخت — amount به تومان
     */
    public function request(float $amount, string $description, string $callbackUrl): array
    {
        if (empty($this->merchantId)) {
            Log::warning('Zarinpal merchant_id تنظیم نشده — حالت تست.');
        }

        try {
            if ($this->sandbox) {
                $response = Http::post($this->baseUrl() . 'PaymentRequest.json', [
                    'MerchantID'  => $this->merchantId,
                    'Amount'      => (int) $amount * 10, // تومان به ریال
                    'Description' => $description,
                    'CallbackURL' => $callbackUrl,
                ]);

                $data = $response->json();

                if (($data['Status'] ?? null) == 100) {
                    return [
                        'success'   => true,
                        'authority' => $data['Authority'],
                        'url'       => $this->startPayUrl($data['Authority']),
                    ];
                }

                return ['success' => false, 'message' => 'کد خطا: ' . ($data['Status'] ?? 'نامشخص')];
            }

            // زرین‌پال v4 (پروداکشن)
            $response = Http::post($this->baseUrl() . 'request.json', [
                'merchant_id'  => $this->merchantId,
                'amount'       => (int) $amount * 10,
                'description'  => $description,
                'callback_url' => $callbackUrl,
            ]);

            $data = $response->json();

            if (($data['data']['code'] ?? null) == 100) {
                $authority = $data['data']['authority'];

                return [
                    'success'   => true,
                    'authority' => $authority,
                    'url'       => $this->startPayUrl($authority),
                ];
            }

            return [
                'success' => false,
                'message' => $data['errors']['message'] ?? 'خطای نامشخص درگاه',
            ];
        } catch (\Throwable $e) {
            Log::error('Zarinpal request error: ' . $e->getMessage());

            return ['success' => false, 'message' => 'خطا در ارتباط با درگاه پرداخت.'];
        }
    }

    /**
     * تایید پرداخت
     */
    public function verify(float $amount, string $authority): array
    {
        try {
            if ($this->sandbox) {
                $response = Http::post($this->baseUrl() . 'PaymentVerification.json', [
                    'MerchantID' => $this->merchantId,
                    'Amount'     => (int) $amount * 10,
                    'Authority'  => $authority,
                ]);

                $data = $response->json();

                if (in_array($data['Status'] ?? null, [100, 101])) {
                    return ['success' => true, 'ref_id' => $data['RefID'] ?? null, 'raw' => $data];
                }

                return ['success' => false, 'raw' => $data];
            }

            $response = Http::post($this->baseUrl() . 'verify.json', [
                'merchant_id' => $this->merchantId,
                'amount'      => (int) $amount * 10,
                'authority'   => $authority,
            ]);

            $data = $response->json();

            if (in_array($data['data']['code'] ?? null, [100, 101])) {
                return ['success' => true, 'ref_id' => $data['data']['ref_id'] ?? null, 'raw' => $data];
            }

            return ['success' => false, 'raw' => $data];
        } catch (\Throwable $e) {
            Log::error('Zarinpal verify error: ' . $e->getMessage());

            return ['success' => false, 'raw' => null];
        }
    }
}
