<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CaseInstallment;
use App\Models\LegalCase;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstallmentController extends Controller
{
    // ─── لیست اقساط موکل ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isSpecial()) {
            abort(403, 'این بخش فقط برای موکلین ویژه در دسترس است.');
        }

        $query = CaseInstallment::where('user_id', $user->id)
            ->with(['case.lawyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // اقساط سررسید‌گذشته ابتدا
        $installments = $query->orderByRaw("FIELD(status, 'pending', 'paid', 'overdue')")
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_pending'  => CaseInstallment::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'total_paid'     => CaseInstallment::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'overdue_count'  => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            'next_due'       => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('due_date')
                ->first(),
        ];

        return view('client.installments.index', compact('installments', 'stats'));
    }

    // ─── شروع پرداخت قسط از طریق زرین‌پال ───────────────────────────────────
    public function pay(CaseInstallment $installment)
    {
        $user = Auth::user();

        if ($installment->user_id !== $user->id) {
            abort(403);
        }

        if ($installment->isPaid()) {
            return back()->with('error', 'این قسط قبلاً پرداخت شده است.');
        }

        try {
            return DB::transaction(function () use ($installment, $user) {

                $payment = Payment::create([
                    'user_id'       => $user->id,
                    'payable_type'  => CaseInstallment::class,
                    'payable_id'    => $installment->id,
                    'tracking_code' => Payment::generateTrackingCode(),
                    'amount'        => $installment->amount,
                    'status'        => 'pending',
                    'gateway'       => 'zarinpal',
                    'description'   => 'پرداخت قسط شماره ' . $installment->installment_number
                        . ' پرونده ' . $installment->case->case_number,
                ]);

                $authority = $this->requestZarinpal($payment, $installment);
                $payment->update(['authority' => $authority]);

                return redirect($this->getStartUrl($authority));
            });
        } catch (\Exception $e) {
            Log::error('Installment payment error: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    // ─── تأیید پرداخت از زرین‌پال ────────────────────────────────────────────
    public function verify(Request $request, Payment $payment)
    {
        $user = Auth::user();

        if ($payment->user_id !== $user->id) {
            abort(403);
        }

        if ($request->query('Status') !== 'OK') {
            DB::transaction(function () use ($payment) {
                $payment->update(['status' => 'failed']);
            });
            return redirect()->route('client.installments.index')
                ->with('error', 'پرداخت لغو شد یا ناموفق بود.');
        }

        try {
            return DB::transaction(function () use ($payment, $request) {

                $result = $this->verifyZarinpal($payment, $request->query('Authority'));

                $payment->update([
                    'status' => 'paid',
                    'ref_id' => $result['data']['ref_id'] ?? null,
                    'paid_at' => now(),
                ]);

                /** @var CaseInstallment $installment */
                $installment = $payment->payable;
                $installment->update([
                    'status'     => 'paid',
                    'paid_at'    => now(),
                    'payment_id' => $payment->id,
                ]);

                // افزایش مبلغ پرداخت‌شده پرونده
                $installment->case->increment('paid_amount', $installment->amount);

                return redirect()->route('client.installments.index')
                    ->with('success', 'قسط با موفقیت پرداخت شد. کد رهگیری: ' . $payment->ref_id);
            });
        } catch (\Exception $e) {
            Log::error('Installment verify error: ' . $e->getMessage());
            return redirect()->route('client.installments.index')
                ->with('error', 'خطا در تأیید پرداخت: ' . $e->getMessage());
        }
    }

    // ─── Zarinpal helpers ────────────────────────────────────────────────────
    private function requestZarinpal(Payment $payment, CaseInstallment $installment): string
    {
        $merchantId = config('services.zarinpal.merchant_id');
        $isSandbox  = config('services.zarinpal.sandbox', true);
        $baseUrl    = $isSandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://api.zarinpal.com/pg/v4/payment';

        $response = Http::timeout(10)->post("{$baseUrl}/request.json", [
            'merchant_id'  => $merchantId,
            'amount'       => (int) ($payment->amount * 10),
            'description'  => $payment->description,
            'callback_url' => route('client.installments.verify', $payment->id),
            'metadata'     => [
                'mobile' => Auth::user()->phone ?? '',
                'email'  => Auth::user()->email ?? '',
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('خطا در اتصال به درگاه پرداخت.');
        }

        $result = $response->json();

        if (!isset($result['data']['code']) || $result['data']['code'] != 100) {
            $msg = $result['errors']['message'] ?? 'خطای ناشناخته';
            throw new \Exception('درگاه پاسخ نامعتبر داد: ' . $msg);
        }

        return $result['data']['authority'];
    }

    private function verifyZarinpal(Payment $payment, string $authority): array
    {
        $merchantId = config('services.zarinpal.merchant_id');
        $isSandbox  = config('services.zarinpal.sandbox', true);
        $baseUrl    = $isSandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://api.zarinpal.com/pg/v4/payment';

        $response = Http::timeout(10)->post("{$baseUrl}/verify.json", [
            'merchant_id' => $merchantId,
            'authority'   => $authority,
            'amount'      => (int) ($payment->amount * 10),
        ]);

        if ($response->failed()) {
            throw new \Exception('خطا در تأیید پرداخت.');
        }

        $result = $response->json();

        if (!isset($result['data']['code']) || $result['data']['code'] != 100) {
            throw new \Exception('پرداخت تأیید نشد.');
        }

        return $result;
    }

    private function getStartUrl(string $authority): string
    {
        $isSandbox = config('services.zarinpal.sandbox', true);
        $base      = $isSandbox
            ? 'https://sandbox.zarinpal.com/pg/StartPay/'
            : 'https://www.zarinpal.com/pg/StartPay/';

        return $base . $authority;
    }
}