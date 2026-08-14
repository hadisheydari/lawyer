<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CaseInstallment;
use App\Models\Payment;
use App\Services\ZarinpalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user->isSpecial()) {
            abort(403, 'این بخش فقط برای موکلین ویژه در دسترس است.');
        }

        $query = CaseInstallment::where('user_id', $user->id)->with(['case.lawyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $installments = $query
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'paid' THEN 2 ELSE 3 END")
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_pending' => CaseInstallment::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
            'total_paid'    => CaseInstallment::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'overdue_count' => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')->where('due_date', '<', now())->count(),
            'next_due' => CaseInstallment::where('user_id', $user->id)
                ->where('status', 'pending')->orderBy('due_date')->first(),
        ];

        return view('client.installments.index', compact('installments', 'stats'));
    }

    public function pay(CaseInstallment $installment)
    {
        if ($installment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($installment->isPaid()) {
            return back()->with('error', 'این قسط قبلاً پرداخت شده است.');
        }

        $payment = Payment::create([
            'user_id'       => Auth::id(),
            'payable_type'  => CaseInstallment::class,
            'payable_id'    => $installment->id,
            'tracking_code' => Payment::generateTrackingCode(),
            'amount'        => $installment->amount,
            'status'        => 'pending',
            'gateway'       => 'zarinpal',
            'description'   => 'پرداخت قسط شماره ' . $installment->installment_number . ' - ' . ($installment->case->title ?? ''),
        ]);

        $installment->update(['payment_id' => $payment->id]);

        $zarinpal = new ZarinpalService();
        $result = $zarinpal->request(
            (float) $payment->amount,
            $payment->description,
            route('client.installments.verify', $payment->id)
        );

        if (! $result['success']) {
            return back()->with('error', $result['message'] ?? 'خطا در اتصال به درگاه پرداخت.');
        }

        $payment->update(['authority' => $result['authority']]);

        return redirect()->away($result['url']);
    }

    public function verify(Request $request, Payment $payment)
    {
        if ($payment->payable_type !== CaseInstallment::class || $payment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->query('Status') !== 'OK') {
            $payment->update(['status' => 'failed']);

            return redirect()->route('client.installments.index')->with('error', 'پرداخت لغو شد یا انجام نشد.');
        }

        $zarinpal = new ZarinpalService();
        $result = $zarinpal->verify((float) $payment->amount, $request->query('Authority'));

        if (! $result['success']) {
            $payment->update(['status' => 'failed', 'gateway_response' => $result['raw'] ?? null]);

            return redirect()->route('client.installments.index')
                ->with('error', 'تایید پرداخت ناموفق بود. در صورت کسر وجه ظرف ۷۲ ساعت بازگشت داده می‌شود.');
        }

        $payment->update([
            'status'           => 'paid',
            'ref_id'           => $result['ref_id'],
            'paid_at'          => now(),
            'gateway_response' => $result['raw'] ?? null,
        ]);

        $installment = $payment->payable;
        $installment->update(['status' => 'paid', 'paid_at' => now()]);
        $installment->case()->increment('paid_amount', $installment->amount);
        if ($installment->case && $installment->case->lawyer) {
    $installment->case->lawyer->notify(new \App\Notifications\PaymentReceivedNotification(
        $payment,
        route('lawyer.cases.show', $installment->case_id)
    ));
}

        return redirect()->route('client.installments.index')
            ->with('success', 'قسط با موفقیت پرداخت شد. کد پیگیری: ' . $result['ref_id']);
    }
}
