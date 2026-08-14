@extends('layouts.lawyer')
@section('title', 'جزئیات پرداخت')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .payment-hero {
        background:linear-gradient(135deg,var(--navy),#1e3a5f);
        border-radius:16px; padding:28px 32px; color:#fff; margin-bottom:25px;
        display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap;
    }
    .ph-left h2 { font-size:1.3rem; font-weight:900; margin:0 0 6px; }
    .ph-left p { color:rgba(255,255,255,0.6); font-size:0.85rem; margin:0; font-family:monospace; }
    .ph-amount { font-size:2rem; font-weight:900; color:var(--gold-main); }

    .badge { padding:5px 14px; border-radius:20px; font-size:0.78rem; font-weight:700; }
    .badge-paid    { background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.3); }
    .badge-pending { background:rgba(245,158,11,0.2); color:#fcd34d; border:1px solid rgba(245,158,11,0.3); }
    .badge-failed  { background:rgba(239,68,68,0.2);  color:#fca5a5; border:1px solid rgba(239,68,68,0.3); }

    .grid-2 { display:grid; grid-template-columns:1fr 320px; gap:25px; align-items:start; }

    .card { background:#fff; border-radius:14px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:0.95rem; font-weight:800; color:var(--navy); margin-bottom:18px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f5f5f5; font-size:0.9rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#888; }
    .info-value { font-weight:700; color:var(--navy); }

    .success-box { background:#f0fdf4; border:1px solid #a7f3d0; border-radius:12px; padding:20px; margin-bottom:16px; display:flex; align-items:center; gap:14px; }
    .success-box i { font-size:2rem; color:#059669; flex-shrink:0; }
    .success-box h4 { font-size:1rem; font-weight:800; color:#065f46; margin:0 0 4px; }
    .success-box p { font-size:0.85rem; color:#047857; margin:0; }

    .failed-box { background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:20px; margin-bottom:16px; display:flex; align-items:center; gap:14px; }
    .failed-box i { font-size:2rem; color:#dc2626; flex-shrink:0; }
    .failed-box h4 { font-size:1rem; font-weight:800; color:#991b1b; margin:0 0 4px; }
    .failed-box p { font-size:0.85rem; color:#b91c1c; margin:0; }

    .client-box { background:linear-gradient(135deg,var(--navy),#1e3a5f); border-radius:14px; padding:22px; color:#fff; text-align:center; margin-bottom:18px; }
    .client-avatar { width:58px; height:58px; border-radius:50%; background:rgba(212,175,55,0.2); border:2px solid rgba(212,175,55,0.5); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:900; color:var(--gold-main); margin:0 auto 10px; }
    .client-box h4 { font-size:1rem; font-weight:800; margin-bottom:4px; }
    .client-box p { color:rgba(255,255,255,0.6); font-size:0.8rem; }

    .btn-back { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px; background:#f1f5f9; color:var(--navy); border-radius:9px; font-weight:700; font-size:0.88rem; text-decoration:none; transition:0.2s; }
    .btn-back:hover { background:var(--navy); color:#fff; }

    .mark-paid-form { border-top:2px solid #f5f0ea; margin-top:16px; padding-top:16px; }
    .form-label { display:block; margin-bottom:6px; font-size:0.85rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:10px 14px; border:1.5px solid #e0e0e0; border-radius:9px; font-family:'Vazirmatn',sans-serif; font-size:0.9rem; outline:none; margin-bottom:12px; }
    .form-input:focus { border-color:var(--gold-main); }
    .btn-mark { width:100%; padding:11px; background:linear-gradient(135deg,var(--gold-main),var(--gold-dark)); color:#fff; border:none; border-radius:9px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:7px; transition:0.2s; }
    .btn-mark:hover { opacity:0.9; }

    @media(max-width:768px) { .grid-2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.payments.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به پرداخت‌ها
</a>

@php
    $isConsultation = $payment->payable_type && str_contains($payment->payable_type, 'Consultation');
    $statusMap = ['paid'=>['l'=>'موفق','c'=>'badge-paid'],'pending'=>['l'=>'در انتظار','c'=>'badge-pending'],'failed'=>['l'=>'ناموفق','c'=>'badge-failed']];
    $s = $statusMap[$payment->status] ?? ['l'=>$payment->status,'c'=>''];
@endphp

<div class="payment-hero">
    <div class="ph-left">
        <h2>{{ $isConsultation ? 'پرداخت مشاوره' : 'پرداخت قسط پرونده' }}</h2>
        <p>{{ $payment->tracking_code }}</p>
        <span class="badge {{ $s['c'] }}" style="margin-top:10px;display:inline-flex;">{{ $s['l'] }}</span>
    </div>
    <div class="ph-amount">{{ fa_number($payment->amount) }} ت</div>
</div>

<div class="grid-2">

    <div>
        {{-- وضعیت --}}
        @if($payment->isPaid())
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                <div>
                    <h4>پرداخت موفق</h4>
                    <p>کد رهگیری بانکی: {{ $payment->ref_id ?? '—' }}</p>
                </div>
            </div>
        @elseif($payment->isFailed())
            <div class="failed-box">
                <i class="fas fa-times-circle"></i>
                <div>
                    <h4>پرداخت ناموفق</h4>
                    <p>تراکنش با موفقیت انجام نشد.</p>
                </div>
            </div>
        @endif

        {{-- اطلاعات پرداخت --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-info-circle"></i> اطلاعات پرداخت</div>
            <div class="info-row"><span class="info-label">مبلغ</span><span class="info-value" style="color:var(--gold-dark);font-size:1.05rem;">{{ fa_number($payment->amount) }} تومان</span></div>
            <div class="info-row"><span class="info-label">درگاه</span><span class="info-value">{{ ucfirst($payment->gateway ?? 'zarinpal') }}</span></div>
            <div class="info-row"><span class="info-label">کد رهگیری</span><span class="info-value" style="font-family:monospace;font-size:0.85rem;">{{ $payment->tracking_code }}</span></div>
            @if($payment->ref_id)
                <div class="info-row"><span class="info-label">کد بانکی</span><span class="info-value" style="font-family:monospace;">{{ $payment->ref_id }}</span></div>
            @endif
            <div class="info-row"><span class="info-label">تاریخ ثبت</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->created_at)->format('Y/m/d H:i') }}</span></div>
            @if($payment->paid_at)
                <div class="info-row"><span class="info-label">تاریخ پرداخت</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->paid_at)->format('Y/m/d H:i') }}</span></div>
            @endif
            @if($payment->description)
                <div class="info-row"><span class="info-label">توضیح</span><span class="info-value" style="font-size:0.85rem;">{{ $payment->description }}</span></div>
            @endif
        </div>

        {{-- جزئیات موضوع پرداخت --}}
        @if($payment->payable)
            <div class="card">
                <div class="card-title"><i class="fas fa-link"></i> موضوع پرداخت</div>
                @if($isConsultation)
                    @php $c = $payment->payable; @endphp
                    <div class="info-row"><span class="info-label">عنوان مشاوره</span><span class="info-value">{{ $c->title ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">نوع</span><span class="info-value">{{ ['chat'=>'چت','call'=>'تلفنی','appointment'=>'حضوری'][$c->type ?? ''] ?? '—' }}</span></div>
                    @if($c->scheduled_at)
                        <div class="info-row"><span class="info-label">تاریخ مشاوره</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d H:i') }}</span></div>
                    @endif
                    <div style="margin-top:14px;">
                        <a href="{{ route('lawyer.consultations.show', $payment->payable_id) }}"
                           style="display:inline-flex;align-items:center;gap:7px;padding:10px 18px;background:var(--navy);color:#fff;border-radius:9px;font-size:0.85rem;font-weight:700;text-decoration:none;">
                            <i class="fas fa-eye"></i> مشاهده مشاوره
                        </a>
                    </div>
                @else
                    @php $inst = $payment->payable; @endphp
                    <div class="info-row"><span class="info-label">پرونده</span><span class="info-value">{{ $inst->case->title ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">شماره قسط</span><span class="info-value">{{ $inst->installment_number ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">سررسید</span><span class="info-value">{{ $inst->due_date ? \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') : '—' }}</span></div>

                    @if($inst && !$inst->isPaid())
                        <div class="mark-paid-form">
                            <div style="font-size:0.88rem;font-weight:700;color:var(--navy);margin-bottom:12px;">
                                <i class="fas fa-hand-holding-usd" style="color:var(--gold-main);margin-left:6px;"></i>ثبت دستی پرداخت
                            </div>
                            <form method="POST" action="{{ route('lawyer.payments.installment.mark-paid', $inst) }}">
                                @csrf
                                <label class="form-label">تاریخ پرداخت</label>
                                <input type="date" name="paid_at" class="form-input" value="{{ date('Y-m-d') }}" required>
                                <label class="form-label">یادداشت (اختیاری)</label>
                                <input type="text" name="notes" class="form-input" placeholder="توضیح پرداخت...">
                                <button type="submit" class="btn-mark">
                                    <i class="fas fa-check"></i> ثبت پرداخت دستی
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($inst->case)
                        <div style="margin-top:14px;">
                            <a href="{{ route('lawyer.cases.show', $inst->case) }}"
                               style="display:inline-flex;align-items:center;gap:7px;padding:10px 18px;background:var(--navy);color:#fff;border-radius:9px;font-size:0.85rem;font-weight:700;text-decoration:none;">
                                <i class="fas fa-briefcase"></i> مشاهده پرونده
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>

    {{-- سایدبار --}}
    <div>
        @if($payment->user)
            <div class="client-box">
                <div class="client-avatar">{{ mb_substr($payment->user->name, 0, 1) }}</div>
                <h4>{{ $payment->user->name }}</h4>
                <p>{{ $payment->user->phone }}</p>
            </div>
        @endif

        <div class="card">
            <div class="card-title"><i class="fas fa-bolt"></i> عملیات</div>
            <a href="{{ route('lawyer.payments.index') }}" class="btn-back">
                <i class="fas fa-list"></i> بازگشت به لیست
            </a>
        </div>
    </div>
</div>

@endsection