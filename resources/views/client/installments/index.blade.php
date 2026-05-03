@extends('layouts.client')

@section('title', 'اقساط و پرداخت‌ها')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .alert-overdue {
        background: #fef2f2; border: 1px solid #fecaca; border-right: 4px solid #dc2626;
        border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 12px; font-size: 0.88rem; color: #991b1b; font-weight: 600;
    }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px; margin-bottom: 25px;
    }
    .stat-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.04); text-align: center; border-bottom: 3px solid transparent; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card .n { font-size: 1.6rem; font-weight: 800; color: var(--navy); display: block; }
    .stat-card .l { font-size: 0.8rem; color: #888; margin-top: 4px; display: block; }

    .filter-bar { background: #fff; padding: 15px 20px; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.04); margin-bottom: 20px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filter-tab { padding: 7px 18px; border-radius: 20px; border: 1.5px solid #e0e0e0; background: #fff; font-family: 'Vazirmatn', sans-serif; font-size: 0.84rem; font-weight: 600; color: #888; cursor: pointer; text-decoration: none; transition: 0.2s; }
    .filter-tab:hover, .filter-tab.active { border-color: var(--navy); background: var(--navy); color: #fff; }

    /* next installment banner */
    .next-inst-banner {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 12px; padding: 20px 24px; color: #fff; margin-bottom: 20px;
        display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;
    }
    .next-inst-info h4 { font-size: 1rem; font-weight: 800; margin: 0 0 5px; }
    .next-inst-info p { font-size: 0.85rem; color: rgba(255,255,255,0.7); margin: 0; }
    .next-inst-amount { font-size: 1.4rem; font-weight: 900; color: var(--gold-main); }
    .btn-pay-big { padding: 11px 24px; background: var(--gold-main); color: #fff; border-radius: 10px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: 0.2s; white-space: nowrap; }
    .btn-pay-big:hover { background: var(--gold-dark); color: #fff; }

    .inst-list { display: flex; flex-direction: column; gap: 14px; }

    .inst-card {
        background: #fff; border-radius: 12px; padding: 20px 22px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;
        display: flex; justify-content: space-between; align-items: center; gap: 16px; transition: 0.3s;
    }
    .inst-card:hover { border-color: var(--gold-main); transform: translateX(-2px); }
    .inst-card.overdue { border-color: #fecaca; background: #fff8f8; }
    .inst-card.paid { opacity: 0.7; }

    .inst-left { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; }
    .inst-num {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: rgba(197,160,89,0.12); border: 2px solid rgba(197,160,89,0.3);
        color: var(--gold-dark); font-weight: 800; font-size: 0.95rem;
        display: flex; align-items: center; justify-content: center;
    }
    .inst-num.paid   { background: #f0fdf4; border-color: #a7f3d0; color: #059669; }
    .inst-num.overdue{ background: #fff5f5; border-color: #fecaca; color: #dc2626; }

    .inst-info h4 { font-size: 0.92rem; font-weight: 700; color: var(--navy); margin: 0 0 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .inst-meta { display: flex; gap: 12px; font-size: 0.75rem; color: #888; flex-wrap: wrap; }
    .inst-meta span { display: flex; align-items: center; gap: 4px; }

    .inst-right { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
    .inst-amount { font-size: 1.05rem; font-weight: 800; color: var(--navy); white-space: nowrap; }
    .inst-amount.paid-amount { color: #059669; }

    .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .badge-paid    { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-overdue { background: #fee2e2; color: #b91c1c; }

    .btn-pay { padding: 7px 16px; background: var(--gold-main); color: #fff; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; white-space: nowrap; }
    .btn-pay:hover { background: var(--gold-dark); color: #fff; }
    .btn-pay.urgent { background: #dc2626; }
    .btn-pay.urgent:hover { background: #b91c1c; }

    .empty-state { text-align: center; padding: 80px 20px; background: #fff; border-radius: 14px; }
    .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 15px; opacity: 0.3; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }

    @media (max-width: 640px) {
        .inst-card { flex-direction: column; align-items: flex-start; }
        .inst-right { width: 100%; justify-content: space-between; }
    }
</style>
@endpush

@section('content')

    <div class="page-header">
        <h2><i class="fas fa-receipt" style="color:var(--gold-main);margin-left:10px;"></i>اقساط و پرداخت‌ها</h2>
    </div>

    @if($stats['overdue_count'] > 0)
        <div class="alert-overdue">
            <i class="fas fa-exclamation-triangle" style="font-size:1.2rem;color:#dc2626;"></i>
            شما <strong>{{ $stats['overdue_count'] }} قسط سررسیدگذشته</strong> دارید. لطفاً در اسرع وقت پرداخت کنید.
        </div>
    @endif

    {{-- آمار --}}
    <div class="stats-grid">
        <div class="stat-card" style="border-bottom-color:#f59e0b;">
            <span class="n">{{ number_format($stats['total_pending'] / 1000000, 1) }}M</span>
            <span class="l">کل مانده (تومان)</span>
        </div>
        <div class="stat-card" style="border-bottom-color:#10b981;">
            <span class="n">{{ number_format($stats['total_paid'] / 1000000, 1) }}M</span>
            <span class="l">کل پرداخت‌شده (تومان)</span>
        </div>
        <div class="stat-card" style="border-bottom-color:#ef4444;">
            <span class="n">{{ $stats['overdue_count'] }}</span>
            <span class="l">قسط سررسیدگذشته</span>
        </div>
    </div>

    {{-- قسط بعدی --}}
    @if($stats['next_due'])
        @php $nxt = $stats['next_due']; $isOverdue = $nxt->due_date < now(); @endphp
        <div class="next-inst-banner">
            <div class="next-inst-info">
                <h4>{{ $isOverdue ? '⚠️ قسط سررسیدگذشته' : '📅 قسط پیش رو' }}</h4>
                <p>
                    {{ $nxt->case->title ?? 'پرونده' }} — قسط {{ $nxt->installment_number }}
                    &nbsp;·&nbsp; سررسید: {{ \Morilog\Jalali\Jalalian::fromCarbon($nxt->due_date)->format('Y/m/d') }}
                </p>
            </div>
            <div class="next-inst-amount">{{ number_format($nxt->amount) }} ت</div>
            <a href="{{ route('client.installments.pay', $nxt) }}" class="btn-pay-big">
                <i class="fas fa-credit-card"></i> پرداخت آنلاین
            </a>
        </div>
    @endif

    {{-- فیلتر --}}
    <div class="filter-bar">
        <a href="{{ route('client.installments.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">همه</a>
        <a href="{{ route('client.installments.index', ['status'=>'pending']) }}" class="filter-tab {{ request('status')==='pending' ? 'active' : '' }}">در انتظار</a>
        <a href="{{ route('client.installments.index', ['status'=>'paid']) }}" class="filter-tab {{ request('status')==='paid' ? 'active' : '' }}">پرداخت‌شده</a>
    </div>

    {{-- لیست اقساط --}}
    <div class="inst-list">
        @forelse($installments as $inst)
            @php
                $isOverdue = $inst->status === 'pending' && $inst->due_date < now();
                $numClass  = $inst->status === 'paid' ? 'paid' : ($isOverdue ? 'overdue' : '');
            @endphp
            <div class="inst-card {{ $inst->status === 'paid' ? 'paid' : ($isOverdue ? 'overdue' : '') }}">
                <div class="inst-left">
                    <div class="inst-num {{ $numClass }}">{{ $inst->installment_number }}</div>
                    <div class="inst-info">
                        <h4>قسط {{ $inst->installment_number }} — {{ $inst->case->title ?? 'پرونده' }}</h4>
                        <div class="inst-meta">
                            <span><i class="fas fa-calendar-alt" style="color:var(--gold-main);"></i> سررسید: {{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}</span>
                            @if($inst->status === 'paid' && $inst->paid_at)
                                <span><i class="fas fa-check-circle" style="color:#10b981;"></i> پرداخت: {{ \Morilog\Jalali\Jalalian::fromCarbon($inst->paid_at)->format('Y/m/d') }}</span>
                            @endif
                            @if($inst->case?->lawyer)
                                <span><i class="fas fa-user-tie" style="color:var(--gold-main);"></i> {{ $inst->case->lawyer->name }}</span>
                            @endif
                            @if($inst->notes)
                                <span><i class="fas fa-sticky-note"></i> {{ $inst->notes }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="inst-right">
                    <span class="inst-amount {{ $inst->status === 'paid' ? 'paid-amount' : '' }}">{{ number_format($inst->amount) }} ت</span>
                    @if($inst->status === 'paid')
                        <span class="badge badge-paid">پرداخت‌شده</span>
                    @elseif($isOverdue)
                        <span class="badge badge-overdue">سررسیدگذشته</span>
                        <a href="{{ route('client.installments.pay', $inst) }}" class="btn-pay urgent">
                            <i class="fas fa-credit-card"></i> پرداخت فوری
                        </a>
                    @else
                        <span class="badge badge-pending">در انتظار</span>
                        <a href="{{ route('client.installments.pay', $inst) }}" class="btn-pay">
                            <i class="fas fa-credit-card"></i> پرداخت
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <p style="color:#aaa;">هیچ قسطی یافت نشد.</p>
            </div>
        @endforelse
    </div>

    @if($installments->hasPages())
        <div class="pagination-wrap">
            @if($installments->onFirstPage())
                <span class="page-btn disabled">قبلی</span>
            @else
                <a href="{{ $installments->previousPageUrl() }}" class="page-btn">قبلی</a>
            @endif
            @foreach($installments->getUrlRange(1, $installments->lastPage()) as $page => $url)
                @if($page == $installments->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
            @if($installments->hasMorePages())
                <a href="{{ $installments->nextPageUrl() }}" class="page-btn">بعدی</a>
            @else
                <span class="page-btn disabled">بعدی</span>
            @endif
        </div>
    @endif

@endsection