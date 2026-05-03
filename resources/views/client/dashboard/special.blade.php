@extends('layouts.client')

@section('title', 'داشبورد موکل ویژه')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2639 100%);
        color: #fff; padding: 35px 40px; border-radius: 15px;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px;
    }
    .welcome-card::after {
        content: '§'; position: absolute; left: -20px; bottom: -40px;
        font-size: 14rem; color: rgba(197,160,89,0.06); font-family: serif; pointer-events: none;
    }
    .welcome-text h2 { font-size: 1.5rem; margin: 0 0 8px; font-weight: 800; }
    .welcome-text p  { opacity: 0.7; margin: 0 0 12px; font-size: 0.9rem; }
    .vip-tag {
        background: rgba(197,160,89,0.2); border: 1px solid var(--gold-main);
        color: var(--gold-main); padding: 4px 14px; border-radius: 20px;
        font-size: 0.82rem; display: inline-flex; align-items: center; gap: 6px;
    }
    .quick-btns { display: flex; gap: 12px; flex-wrap: wrap; }
    .q-btn {
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
        color: #fff; padding: 10px 18px; border-radius: 8px;
        cursor: pointer; transition: 0.3s; display: flex; align-items: center;
        gap: 8px; font-family: 'Vazirmatn', sans-serif; font-size: 0.88rem;
        text-decoration: none;
    }
    .q-btn:hover { background: var(--gold-main); border-color: var(--gold-main); color: var(--navy); }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 18px; margin-bottom: 25px;
    }
    .stat-card {
        background: #fff; padding: 22px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-bottom: 3px solid transparent; transition: 0.3s; text-align: center;
    }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin: 0 auto 12px;
    }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--navy); display: block; line-height: 1; }
    .stat-label { font-size: 0.82rem; color: #888; margin-top: 6px; display: block; }

    .two-col { display: grid; grid-template-columns: 1fr 340px; gap: 22px; align-items: start; }

    .section-box {
        background: #fff; padding: 25px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 22px;
    }
    .section-title {
        font-size: 1rem; font-weight: 800; color: var(--navy);
        margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 14px; border-bottom: 2px solid #f5f0ea;
    }
    .section-title span { display: flex; align-items: center; gap: 8px; }
    .section-title i { color: var(--gold-main); }
    .view-all { font-size: 0.8rem; color: var(--gold-dark); text-decoration: none; display: flex; align-items: center; gap: 5px; }
    .view-all:hover { color: var(--gold-main); }

    /* پرونده‌ها */
    .case-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px 0; border-bottom: 1px solid #f5f5f5; gap: 15px;
    }
    .case-item:last-child { border-bottom: none; }
    .case-left { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
    .case-dot { width: 10px; height: 10px; border-radius: 50%; background: #10b981; flex-shrink: 0; }
    .case-info h4 {
        font-size: 0.9rem; font-weight: 700; color: var(--navy);
        margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .case-info span { font-size: 0.78rem; color: #888; }

    /* اقساط */
    .inst-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 14px; border-radius: 10px; margin-bottom: 10px;
        background: #fdfbf7; border: 1px solid #f0ebe0; gap: 14px;
    }
    .inst-item.overdue { background: #fff5f5; border-color: #fecaca; }
    .inst-left { display: flex; align-items: center; gap: 12px; }
    .inst-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .inst-info h4 { font-size: 0.88rem; font-weight: 700; color: var(--navy); margin: 0 0 3px; }
    .inst-info p { font-size: 0.75rem; color: #888; margin: 0; }
    .inst-amount { font-size: 1rem; font-weight: 800; color: var(--gold-dark); white-space: nowrap; }

    /* مالی */
    .finance-bar { margin-bottom: 12px; }
    .finance-labels { display: flex; justify-content: space-between; font-size: 0.78rem; color: #888; margin-bottom: 6px; }
    .progress-track { height: 8px; background: #f0f0f0; border-radius: 20px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--gold-main), var(--gold-dark)); border-radius: 20px; transition: width 0.8s ease; }

    .fin-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 16px; }
    .fin-item { text-align: center; padding: 14px; border-radius: 10px; background: #f8fafc; }
    .fin-item .n { font-size: 1.1rem; font-weight: 900; color: var(--navy); display: block; }
    .fin-item .l { font-size: 0.72rem; color: #888; margin-top: 3px; display: block; }
    .fin-item.green { background: #f0fdf4; }
    .fin-item.green .n { color: #059669; }
    .fin-item.amber { background: #fffbeb; }
    .fin-item.amber .n { color: #d97706; }

    .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .badge-active  { background: #d1fae5; color: #065f46; }
    .badge-on_hold { background: #fef3c7; color: #b45309; }
    .badge-closed, .badge-won, .badge-lost { background: #f1f5f9; color: #64748b; }
    .badge-overdue { background: #fee2e2; color: #b91c1c; }

    .btn-pay {
        padding: 7px 16px; background: var(--gold-main); color: #fff;
        border-radius: 8px; font-size: 0.8rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        transition: 0.2s; white-space: nowrap;
    }
    .btn-pay:hover { background: var(--gold-dark); color: #fff; }

    .btn-sm {
        padding: 7px 14px; background: var(--navy); color: #fff;
        border-radius: 8px; font-size: 0.8rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;
    }
    .btn-sm:hover { background: var(--gold-main); color: #fff; }

    .empty-state { text-align: center; padding: 40px 20px; color: #aaa; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: 0.35; }
    .empty-state p { font-size: 0.88rem; }

    @media (max-width: 960px) { .two-col { grid-template-columns: 1fr; } }
    @media (max-width: 768px) { .welcome-card { flex-direction: column; gap: 20px; } .quick-btns { justify-content: center; } }
</style>
@endpush

@section('content')

    <div class="welcome-card">
        <div class="welcome-text">
            <h2>سلام، {{ $user->name }} عزیز</h2>
            <p>پنل اختصاصی موکلین ویژه دفتر ابدالی و جوشقانی</p>
            <span class="vip-tag"><i class="fas fa-crown"></i> موکل ویژه دفتر</span>
        </div>
        <div class="quick-btns">
            <a href="{{ route('client.chat.index') }}" class="q-btn">
                <i class="fas fa-comment-dots"></i> پیام به وکیل
                @if($unreadMessages > 0)
                    <span style="background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:0.65rem;">{{ $unreadMessages }}</span>
                @endif
            </a>
            <a href="{{ route('client.installments.index') }}" class="q-btn">
                <i class="fas fa-money-bill-wave"></i> پرداخت اقساط
                @if($overdueInstallments > 0)
                    <span style="background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:0.65rem;">{{ $overdueInstallments }}</span>
                @endif
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card" style="border-bottom-color:#3b82f6;">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="fas fa-briefcase"></i></div>
            <span class="stat-value">{{ $activeCases }}</span>
            <span class="stat-label">پرونده فعال</span>
        </div>
        <div class="stat-card" style="border-bottom-color:#10b981;">
            <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-check-double"></i></div>
            <span class="stat-value">{{ $closedCases }}</span>
            <span class="stat-label">پرونده بسته‌شده</span>
        </div>
        <div class="stat-card" style="border-bottom-color:var(--gold-main);">
            <div class="stat-icon" style="background:rgba(197,160,89,0.15);color:var(--gold-dark);"><i class="fas fa-money-bill-wave"></i></div>
            <span class="stat-value">{{ $overdueInstallments }}</span>
            <span class="stat-label">قسط سررسیدگذشته</span>
        </div>
        <div class="stat-card" style="border-bottom-color:#ef4444;">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="far fa-envelope"></i></div>
            <span class="stat-value">{{ $unreadMessages }}</span>
            <span class="stat-label">پیام نخوانده</span>
        </div>
    </div>

    <div class="two-col">
        {{-- ستون اصلی --}}
        <div>
            {{-- آخرین پرونده‌ها --}}
            <div class="section-box">
                <div class="section-title">
                    <span><i class="fas fa-briefcase"></i> آخرین پرونده‌ها</span>
                    <a href="{{ route('client.cases.index') }}" class="view-all">همه پرونده‌ها <i class="fas fa-arrow-left"></i></a>
                </div>

                @forelse($cases as $case)
                    @php
                        $statusLabel = ['active'=>'فعال','on_hold'=>'معلق','closed'=>'بسته','won'=>'برنده','lost'=>'بازنده'][$case->current_status] ?? $case->current_status;
                        $dotColor = $case->current_status === 'active' ? '#10b981' : ($case->current_status === 'on_hold' ? '#f59e0b' : '#94a3b8');
                    @endphp
                    <div class="case-item">
                        <div class="case-left">
                            <div class="case-dot" style="background:{{ $dotColor }};"></div>
                            <div class="case-info">
                                <h4>{{ $case->title }}</h4>
                                <span>
                                    # {{ $case->case_number }}
                                    &nbsp;·&nbsp; {{ $case->lawyer->name ?? '—' }}
                                    @if($case->statusLogs->first())
                                        &nbsp;·&nbsp; {{ $case->statusLogs->first()->status_title }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                            <span class="badge badge-{{ $case->current_status }}">{{ $statusLabel }}</span>
                            <a href="{{ route('client.cases.show', $case) }}" class="btn-sm">جزئیات</a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>هیچ پرونده‌ای ثبت نشده است.</p>
                    </div>
                @endforelse
            </div>

            {{-- اقساط پیش رو --}}
            <div class="section-box">
                <div class="section-title">
                    <span><i class="fas fa-receipt"></i> اقساط پیش رو</span>
                    <a href="{{ route('client.installments.index') }}" class="view-all">همه اقساط <i class="fas fa-arrow-left"></i></a>
                </div>

                @forelse($pendingInstallments as $inst)
                    @php $isOverdue = $inst->due_date < now(); @endphp
                    <div class="inst-item {{ $isOverdue ? 'overdue' : '' }}">
                        <div class="inst-left">
                            <div class="inst-icon" style="background:{{ $isOverdue ? '#fee2e2' : 'rgba(197,160,89,0.1)' }};color:{{ $isOverdue ? '#dc2626' : 'var(--gold-dark)' }};">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div class="inst-info">
                                <h4>قسط {{ $inst->installment_number }} — {{ $inst->case->title ?? 'پرونده' }}</h4>
                                <p>
                                    سررسید: {{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}
                                    @if($isOverdue) &nbsp;<span style="color:#dc2626;font-weight:700;">● سررسیدگذشته</span> @endif
                                </p>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                            <span class="inst-amount">{{ number_format($inst->amount) }} ت</span>
                            <a href="{{ route('client.installments.pay', $inst) }}" class="btn-pay">
                                <i class="fas fa-credit-card"></i> پرداخت
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle" style="color:#10b981;opacity:0.5;"></i>
                        <p>همه اقساط تسویه شده‌اند.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- سایدبار --}}
        <div>
            {{-- خلاصه مالی --}}
            <div class="section-box">
                <div class="section-title">
                    <span><i class="fas fa-wallet"></i> خلاصه مالی</span>
                </div>

                <div class="fin-grid">
                    <div class="fin-item">
                        <span class="n">{{ number_format($totalFee / 1000000, 1) }}M</span>
                        <span class="l">کل حق‌الوکاله (ت)</span>
                    </div>
                    <div class="fin-item green">
                        <span class="n">{{ number_format($totalPaid / 1000000, 1) }}M</span>
                        <span class="l">پرداخت‌شده (ت)</span>
                    </div>
                    <div class="fin-item amber">
                        <span class="n">{{ number_format($totalRemain / 1000000, 1) }}M</span>
                        <span class="l">باقی‌مانده (ت)</span>
                    </div>
                </div>

                @if($totalFee > 0)
                    <div class="finance-bar">
                        <div class="finance-labels">
                            <span>میزان پرداخت</span>
                            <span>{{ $totalFee > 0 ? round($totalPaid / $totalFee * 100) : 0 }}٪</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:{{ $totalFee > 0 ? min(100, round($totalPaid / $totalFee * 100)) : 0 }}%;"></div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('client.installments.index') }}" class="btn-sm" style="width:100%;justify-content:center;margin-top:8px;">
                    <i class="fas fa-list-ul"></i> مشاهده همه اقساط
                </a>
            </div>

            {{-- دسترسی سریع --}}
            <div class="section-box">
                <div class="section-title">
                    <span><i class="fas fa-bolt"></i> دسترسی سریع</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('client.cases.index') }}" style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:10px;color:var(--navy);text-decoration:none;font-weight:600;font-size:0.88rem;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                        <i class="fas fa-briefcase" style="color:var(--gold-main);width:18px;text-align:center;"></i> پرونده‌های من
                    </a>
                    <a href="{{ route('client.installments.index') }}" style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:10px;color:var(--navy);text-decoration:none;font-weight:600;font-size:0.88rem;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                        <i class="fas fa-receipt" style="color:var(--gold-main);width:18px;text-align:center;"></i> اقساط و پرداخت‌ها
                    </a>
                    <a href="{{ route('client.chat.index') }}" style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:10px;color:var(--navy);text-decoration:none;font-weight:600;font-size:0.88rem;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                        <i class="fas fa-comments" style="color:var(--gold-main);width:18px;text-align:center;"></i> گفتگو با وکیل
                        @if($unreadMessages > 0)
                            <span style="margin-right:auto;background:#ef4444;color:#fff;font-size:0.68rem;padding:1px 7px;border-radius:10px;font-weight:800;">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('client.profile') }}" style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:10px;color:var(--navy);text-decoration:none;font-weight:600;font-size:0.88rem;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                        <i class="fas fa-user-edit" style="color:var(--gold-main);width:18px;text-align:center;"></i> ویرایش پروفایل
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection