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

    // ─── لیست پرداخت‌ها ───────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        // پرداخت‌های مربوط به مشاوره‌های این وکیل
        $consultationPaymentIds = Consultation::where('lawyer_id', $lawyer->id)
            ->whereNotNull('payment_id')
            ->pluck('payment_id');

        // پرداخت‌های مربوط به اقساط پرونده‌های این وکیل
        $installmentPaymentIds = CaseInstallment::whereHas(
            'case', fn($q) => $q->where('lawyer_id', $lawyer->id)
        )->whereNotNull('payment_id')->pluck('payment_id');

        $allPaymentIds = $consultationPaymentIds->merge($installmentPaymentIds)->unique();

        $query = Payment::whereIn('id', $allPaymentIds)->with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('tracking_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $payments = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_paid'    => Payment::whereIn('id', $allPaymentIds)->where('status', 'paid')->sum('amount'),
            'total_pending' => Payment::whereIn('id', $allPaymentIds)->where('status', 'pending')->sum('amount'),
            'count_paid'    => Payment::whereIn('id', $allPaymentIds)->where('status', 'paid')->count(),
            'count_pending' => Payment::whereIn('id', $allPaymentIds)->where('status', 'pending')->count(),
        ];

        return view('lawyer.payments.index', compact('payments', 'stats'));
    }

    // ─── جزئیات پرداخت ───────────────────────────────────────────────────────
    public function show(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->load(['user', 'payable']);

        return view('lawyer.payments.show', compact('payment'));
    }

    // ─── ثبت دستی پرداخت قسط (بدون درگاه) ──────────────────────────────────
    public function markInstallmentPaid(Request $request, CaseInstallment $installment)
    {
        // بررسی تعلق قسط به پرونده این وکیل
        if ($installment->case->lawyer_id !== $this->lawyer()->id) {
            abort(403);
        }

        $request->validate([
            'paid_at' => 'required|date',
            'notes'   => 'nullable|string|max:500',
        ], [
            'paid_at.required' => 'تاریخ پرداخت الزامی است.',
        ]);

        DB::transaction(function () use ($request, $installment) {
            $installment->update([
                'status'  => 'paid',
                'paid_at' => $request->paid_at,
                'notes'   => $request->notes,
            ]);

            // به‌روزرسانی مبلغ پرداخت‌شده پرونده
            $case = $installment->case;
            $case->increment('paid_amount', $installment->amount);
        });

        return back()->with('success', 'قسط به عنوان پرداخت‌شده ثبت شد.');
    }

    // ─── بررسی دسترسی به پرداخت ──────────────────────────────────────────────
    private function authorizePayment(Payment $payment): void
    {
        $lawyer = $this->lawyer();

        $allowed = false;

        if ($payment->payable_type === Consultation::class) {
            $allowed = Consultation::where('id', $payment->payable_id)
                ->where('lawyer_id', $lawyer->id)
                ->exists();
        } elseif ($payment->payable_type === CaseInstallment::class) {
            $allowed = CaseInstallment::where('id', $payment->payable_id)
                ->whereHas('case', fn($q) => $q->where('lawyer_id', $lawyer->id))
                ->exists();
        }

        if (!$allowed) {
            abort(403, 'شما دسترسی به این پرداخت را ندارید.');
        }
    }
}