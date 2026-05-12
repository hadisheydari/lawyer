<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\CaseInstallment;
use App\Models\Consultation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        // --- بخش اول: تراکنش‌های درگاه زرین‌پال (Payments) ---
        $consultationPaymentIds = Consultation::where('lawyer_id', $lawyer->id)
            ->whereNotNull('payment_id')->pluck('payment_id');

        $installmentPaymentIds = CaseInstallment::whereHas('case', fn($q) => $q->where('lawyer_id', $lawyer->id))
            ->whereNotNull('payment_id')->pluck('payment_id');

        $allPaymentIds = $consultationPaymentIds->merge($installmentPaymentIds)->unique();

        $payments = Payment::whereIn('id', $allPaymentIds)
            ->with('user')
            ->latest()
            ->paginate(10, ['*'], 'payments_page');

        // --- بخش دوم: اقساط پرداخت نشده برای تایید دستی (CaseInstallments) ---
        $pendingInstallments = CaseInstallment::whereHas('case', fn($q) => $q->where('lawyer_id', $lawyer->id))
            ->where('status', '!=', 'paid')
            ->with(['case', 'case.user'])
            ->orderBy('due_date', 'asc')
            ->paginate(10, ['*'], 'installments_page');

        // --- آمار مالی ---
        $stats = [
            'total_online_paid' => Payment::whereIn('id', $allPaymentIds)->where('status', 'paid')->sum('amount'),
            'total_pending_installments' => CaseInstallment::whereHas('case', fn($q) => $q->where('lawyer_id', $lawyer->id))
                                                ->where('status', '!=', 'paid')->sum('amount'),
        ];

        return view('lawyer.payments.index', compact('payments', 'pendingInstallments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->load(['user', 'payable']);
        return view('lawyer.payments.show', compact('payment'));
    }

    public function markInstallmentPaid(Request $request, CaseInstallment $installment)
    {
        if ($installment->case->lawyer_id !== $this->lawyer()->id) {
            abort(403);
        }

        DB::transaction(function () use ($request, $installment) {
            $installment->update([
                'status'  => 'paid',
                'paid_at' => $request->paid_at ?? now(),
                'notes'   => 'تایید دستی توسط وکیل',
            ]);
            $installment->case->increment('paid_amount', $installment->amount);
        });

        return back()->with('success', 'قسط با موفقیت به عنوان پرداخت‌شده ثبت شد.');
    }

    private function authorizePayment(Payment $payment): void
    {
        // ... (همان کدهای قبلی خودتان بدون تغییر)
        $lawyer = $this->lawyer();
        $allowed = false;
        if ($payment->payable_type === Consultation::class) {
            $allowed = Consultation::where('id', $payment->payable_id)->where('lawyer_id', $lawyer->id)->exists();
        } elseif ($payment->payable_type === CaseInstallment::class) {
            $allowed = CaseInstallment::where('id', $payment->payable_id)->whereHas('case', fn($q) => $q->where('lawyer_id', $lawyer->id))->exists();
        }
        if (!$allowed) abort(403, 'شما دسترسی به این پرداخت را ندارید.');
    }
}