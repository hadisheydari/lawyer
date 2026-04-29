@extends('layouts.client')

@section('title', 'جزئیات مشاوره')

@push('styles')
<style>
    .back-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--gold-dark); font-weight: 600; font-size: 0.9rem;
        text-decoration: none; margin-bottom: 20px;
    }
    .back-link:hover { color: var(--gold-main); }

    .detail-grid { display: grid; grid-template-columns: 1fr 320px; gap: 25px; }

    .detail-card {
        background: #fff; border-radius: 14px; padding: 28px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .detail-card h3 {
        font-size: 1.05rem; font-weight: 800; color: var(--navy);
        margin-bottom: 20px; padding-bottom: 12px;
        border-bottom: 2px solid #f5f0ea;
        display: flex; align-items: center; gap: 8px;
    }
    .detail-card h3 i { color: var(--gold-main); }

    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.9rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #888; }
    .info-value { font-weight: 700; color: var(--navy); }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .badge-pending     { background: #fef3c7; color: #b45309; }
    .badge-confirmed   { background: #dbeafe; color: #1d4ed8; }
    .badge-in_progress { background: #ede9fe; color: #6d28d9; }
    .badge-completed   { background: #d1fae5; color: #065f46; }
    .badge-cancelled   { background: #fee2e2; color: #b91c1c; }
    .badge-rejected    { background: #fee2e2; color: #b91c1c; }

    .lawyer-info-box {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 14px; padding: 25px; color: #fff; text-align: center;
        margin-bottom: 20px;
    }
    .lawyer-avatar-lg {
        width: 70px; height: 70px; border-radius: 50%;
        background: rgba(197,160,89,0.2); border: 3px solid rgba(197,160,89,0.5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 900; color: var(--gold-main);
        margin: 0 auto 12px;
    }
    .lawyer-info-box h4 { font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
    .lawyer-info-box p { color: rgba(255,255,255,0.6); font-size: 0.8rem; }

    .action-btns { display: flex; flex-direction: column; gap: 10px; }
    .btn-action {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px; border-radius: 10px; font-weight: 700;
        font-size: 0.9rem; text-decoration: none; transition: 0.2s; cursor: pointer;
        border: none; font-family: 'Vazirmatn', sans-serif; width: 100%;
    }
    .btn-navy { background: var(--navy); color: #fff; }
    .btn-navy:hover { opacity: 0.9; color: #fff; }
    .btn-gold-c { background: linear-gradient(135deg, var(--gold-main), var(--gold-dark)); color: #fff; }
    .btn-gold-c:hover { opacity: 0.9; color: #fff; }
    .btn-outline-r { background: none; border: 1.5px solid #fecaca; color: #dc2626; }
    .btn-outline-r:hover { background: #fef2f2; }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<a href="{{ route('client.consultations.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به لیست مشاوره‌ها
</a>

@php
    $statusMap = [
        'pending'     => ['label' => 'در انتظار تأیید', 'class' => 'badge-pending'],
        'confirmed'   => ['label' => 'تأیید شده',       'class' => 'badge-confirmed'],
        'in_progress' => ['label' => 'در حال انجام',    'class' => 'badge-in_progress'],
        'completed'   => ['label' => 'تکمیل شده',       'class' => 'badge-completed'],
        'cancelled'   => ['label' => 'لغو شده',          'class' => 'badge-cancelled'],
        'rejected'    => ['label' => 'رد شده',           'class' => 'badge-rejected'],
    ];
    $s = $statusMap[$consultation->status] ?? ['label' => $consultation->status, 'class' => ''];

    $typeLabels = ['chat' => 'چت متنی', 'call' => 'تماس تلفنی', 'appointment' => 'مشاوره حضوری'];
@endphp

<div class="detail-grid">

    {{-- اطلاعات اصلی --}}
    <div>
        <div class="detail-card">
            <h3><i class="fas fa-info-circle"></i> اطلاعات مشاوره</h3>

            <div class="info-row">
                <span class="info-label">عنوان</span>
                <span class="info-value">{{ $consultation->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">نوع مشاوره</span>
                <span class="info-value">{{ $typeLabels[$consultation->type] ?? $consultation->type }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">وضعیت</span>
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
            @if($consultation->scheduled_at)
                <div class="info-row">
                    <span class="info-label">تاریخ و ساعت</span>
                    <span class="info-value">
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d') }}
                        ساعت {{ $consultation->scheduled_at->format('H:i') }}
                    </span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">هزینه</span>
                <span class="info-value" style="color:var(--gold-dark);">{{ number_format($consultation->price) }} تومان</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاریخ ثبت</span>
                <span class="info-value">
                    {{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->created_at)->format('Y/m/d') }}
                </span>
            </div>

            @if($consultation->description)
                <div style="margin-top:20px;padding:15px;background:#fdfbf7;border-radius:10px;border-right:3px solid var(--gold-main);">
                    <p style="font-size:0.88rem;color:#666;margin:0;line-height:1.9;">{{ $consultation->description }}</p>
                </div>
            @endif

            @if($consultation->cancellation_reason)
                <div style="margin-top:15px;padding:15px;background:#fef2f2;border-radius:10px;border-right:3px solid #dc2626;">
                    <p style="font-size:0.85rem;color:#b91c1c;margin:0;">
                        <strong>دلیل لغو:</strong> {{ $consultation->cancellation_reason }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- سایدبار --}}
    <div>
        {{-- وکیل --}}
        @if($consultation->lawyer)
            <div class="lawyer-info-box">
                <div class="lawyer-avatar-lg">{{ mb_substr($consultation->lawyer->name, 0, 1) }}</div>
                <h4>{{ $consultation->lawyer->name }}</h4>
                <p>وکیل پایه {{ $consultation->lawyer->license_grade }} دادگستری</p>
            </div>
        @endif

        {{-- دکمه‌های عملیات --}}
        <div class="detail-card">
            <h3><i class="fas fa-bolt"></i> عملیات</h3>
            <div class="action-btns">
                @if(in_array($consultation->status, ['confirmed', 'in_progress']))
                    <a href="{{ route('client.chat.index') }}" class="btn-action btn-navy">
                        <i class="fas fa-comment-dots"></i> ارسال پیام به وکیل
                    </a>
                @endif
                <a href="{{ route('reserve.index') }}" class="btn-action btn-gold-c">
                    <i class="fas fa-calendar-plus"></i> رزرو نوبت جدید
                </a>
                <a href="{{ route('client.consultations.index') }}" class="btn-action btn-outline-r">
                    <i class="fas fa-list"></i> بازگشت به لیست
                </a>
            </div>
        </div>
    </div>

</div>

@endsection