@extends('layouts.client')

@section('title', 'پرونده‌های من')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-mini {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px; margin-bottom: 25px;
    }
    .stat-mini-card {
        background: #fff; padding: 20px; border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04); text-align: center;
    }
    .stat-mini-card .n { font-size: 1.8rem; font-weight: 800; color: var(--navy); display: block; }
    .stat-mini-card .l { font-size: 0.8rem; color: #888; margin-top: 3px; display: block; }

    .cases-list { display: flex; flex-direction: column; gap: 18px; }

    .case-card {
        background: #fff; border-radius: 14px; padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0; border-right: 4px solid transparent;
        transition: 0.3s;
    }
    .case-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .case-card.active  { border-right-color: #10b981; }
    .case-card.on_hold { border-right-color: #f59e0b; }
    .case-card.closed, .case-card.won, .case-card.lost { border-right-color: #94a3b8; }

    .case-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; gap:12px; }
    .case-num { font-size: 0.75rem; color: var(--gold-dark); font-weight: 700; margin-bottom: 5px; }
    .case-title { font-size: 1.05rem; font-weight: 800; color: var(--navy); margin: 0; }
    .case-lawyer { font-size: 0.8rem; color: #888; margin-top: 4px; display: flex; align-items: center; gap: 5px; }

    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; flex-shrink: 0; }
    .badge-active  { background: #d1fae5; color: #065f46; }
    .badge-on_hold { background: #fef3c7; color: #b45309; }
    .badge-closed  { background: #f1f5f9; color: #64748b; }
    .badge-won     { background: #d1fae5; color: #065f46; }
    .badge-lost    { background: #fee2e2; color: #b91c1c; }

    .case-body { display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .case-stat { text-align: center; padding: 12px; background: #f8fafc; border-radius: 8px; }
    .case-stat .n { font-size: 1.1rem; font-weight: 800; color: var(--navy); display: block; }
    .case-stat .l { font-size: 0.72rem; color: #888; }

    .progress-wrap { margin-bottom: 14px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 0.75rem; color: #888; margin-bottom: 5px; }
    .progress-bar { height: 7px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--gold-main), var(--gold-dark)); border-radius: 10px; }

    .case-footer { display:flex; justify-content:space-between; align-items:center; gap: 12px; }
    .case-meta-tags { display: flex; gap: 8px; flex-wrap: wrap; }
    .meta-tag { font-size: 0.75rem; color: #888; display: flex; align-items: center; gap: 4px; }

    .btn-sm {
        padding: 8px 18px; background: var(--navy); color: #fff; border-radius: 8px;
        font-size: 0.82rem; font-weight: 700; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;
        flex-shrink: 0;
    }
    .btn-sm:hover { background: var(--gold-main); color: #fff; }

    .latest-status {
        padding: 12px 15px; background: #fdfbf7; border-radius: 8px;
        border-right: 3px solid var(--gold-main); margin-bottom: 14px;
        font-size: 0.82rem; color: #555;
    }
    .latest-status strong { color: var(--navy); }

    .empty-state { text-align: center; padding: 80px 20px; background: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
    .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 15px; opacity: 0.3; }
    .empty-state p { color: #aaa; margin-bottom: 20px; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }

    @media (max-width: 600px) {
        .case-body { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')

    <div class="page-header">
        <h2><i class="fas fa-briefcase" style="color:var(--gold-main);margin-left:10px;"></i>پرونده‌های من</h2>
    </div>

    <div class="stats-mini">
        <div class="stat-mini-card" style="border-bottom:3px solid #10b981;">
            <span class="n">{{ $activeCases }}</span>
            <span class="l">پرونده فعال</span>
        </div>
        <div class="stat-mini-card" style="border-bottom:3px solid #94a3b8;">
            <span class="n">{{ $closedCases }}</span>
            <span class="l">پرونده بسته‌شده</span>
        </div>
        <div class="stat-mini-card" style="border-bottom:3px solid var(--gold-main);">
            <span class="n">{{ $cases->total() }}</span>
            <span class="l">کل پرونده‌ها</span>
        </div>
    </div>

    <div class="cases-list">
        @forelse($cases as $case)
            @php
                $statusLabel = ['active'=>'فعال','on_hold'=>'معلق','closed'=>'بسته','won'=>'برنده','lost'=>'بازنده'][$case->current_status] ?? $case->current_status;
                $latestLog = $case->statusLogs->first();
            @endphp
            <div class="case-card {{ $case->current_status }}">
                <div class="case-header">
                    <div>
                        <div class="case-num"># {{ $case->case_number }}</div>
                        <h3 class="case-title">{{ $case->title }}</h3>
                        <div class="case-lawyer">
                            <i class="fas fa-user-tie" style="color:var(--gold-main);"></i>
                            {{ $case->lawyer->name ?? '—' }}
                            @if($case->service)
                                &nbsp;·&nbsp; <i class="fas fa-tag" style="color:var(--gold-main);"></i> {{ $case->service->title }}
                            @endif
                        </div>
                    </div>
                    <span class="badge badge-{{ $case->current_status }}">{{ $statusLabel }}</span>
                </div>

                @if($latestLog)
                    <div class="latest-status">
                        <strong>آخرین وضعیت:</strong> {{ $latestLog->status_title }}
                        &nbsp;·&nbsp; {{ \Morilog\Jalali\Jalalian::fromCarbon($latestLog->status_date)->format('Y/m/d') }}
                    </div>
                @endif

                <div class="case-body">
                    <div class="case-stat">
                        <span class="n">{{ number_format($case->total_fee / 1000000, 1) }}M</span>
                        <span class="l">حق‌الوکاله (ت)</span>
                    </div>
                    <div class="case-stat" style="background:#f0fdf4;">
                        <span class="n" style="color:#059669;">{{ number_format($case->paid_amount / 1000000, 1) }}M</span>
                        <span class="l">پرداخت‌شده (ت)</span>
                    </div>
                    <div class="case-stat" style="background:#fffbeb;">
                        <span class="n" style="color:#d97706;">{{ number_format(max(0, $case->total_fee - $case->paid_amount) / 1000000, 1) }}M</span>
                        <span class="l">باقی‌مانده (ت)</span>
                    </div>
                </div>

                <div class="progress-wrap">
                    <div class="progress-label">
                        <span>پیشرفت پرداخت</span>
                        <span>{{ $case->progress_percent }}٪</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $case->progress_percent }}%;"></div>
                    </div>
                </div>

                <div class="case-footer">
                    <div class="case-meta-tags">
                        <span class="meta-tag">
                            <i class="fas fa-calendar-alt" style="color:var(--gold-main);"></i>
                            افتتاح: {{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}
                        </span>
                        @if($case->closed_at)
                            <span class="meta-tag">
                                <i class="fas fa-flag" style="color:#94a3b8;"></i>
                                بسته: {{ \Morilog\Jalali\Jalalian::fromCarbon($case->closed_at)->format('Y/m/d') }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('client.cases.show', $case) }}" class="btn-sm">
                        مشاهده جزئیات <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>هیچ پرونده‌ای برای شما ثبت نشده است.</p>
                <p style="font-size:0.85rem;color:#bbb;">برای ثبت پرونده با دفتر تماس بگیرید.</p>
            </div>
        @endforelse
    </div>

    @if($cases->hasPages())
        <div class="pagination-wrap">
            @if($cases->onFirstPage())
                <span class="page-btn disabled">قبلی</span>
            @else
                <a href="{{ $cases->previousPageUrl() }}" class="page-btn">قبلی</a>
            @endif
            @foreach($cases->getUrlRange(1, $cases->lastPage()) as $page => $url)
                @if($page == $cases->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
            @if($cases->hasMorePages())
                <a href="{{ $cases->nextPageUrl() }}" class="page-btn">بعدی</a>
            @else
                <span class="page-btn disabled">بعدی</span>
            @endif
        </div>
    @endif

@endsection