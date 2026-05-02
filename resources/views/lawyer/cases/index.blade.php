@extends('layouts.lawyer')
@section('title', 'پرونده‌های حقوقی')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }
    .btn-new { background:linear-gradient(135deg,var(--gold-main),var(--gold-dark)); color:var(--navy); padding:10px 22px; border-radius:10px; font-weight:800; font-size:0.9rem; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:0.3s; }
    .btn-new:hover { transform:translateY(-2px); color:var(--navy); }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; }
    .stat-n { font-size:2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar { background:#fff; padding:18px 22px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:9px 14px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; outline:none; transition:0.2s; }
    .filter-bar input { flex:1; min-width:180px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--gold-main); }
    .btn-filter { background:var(--navy); color:#fff; padding:9px 18px; border:none; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:6px; }

    .cases-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:18px; }
    .case-card { background:#fff; border-radius:14px; padding:22px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border:1px solid #f0f0f0; transition:0.3s; position:relative; overflow:hidden; }
    .case-card:hover { transform:translateY(-3px); border-color:var(--gold-main); }
    .case-card::before { content:''; position:absolute; right:0; top:0; bottom:0; width:4px; }
    .case-card.active::before { background:#10b981; }
    .case-card.on_hold::before { background:#f59e0b; }
    .case-card.closed::before, .case-card.won::before, .case-card.lost::before { background:#ef4444; }

    .case-num { font-size:0.75rem; color:var(--gold-dark); font-weight:700; margin-bottom:6px; }
    .case-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:8px; }
    .case-meta { font-size:0.8rem; color:#888; display:flex; gap:12px; flex-wrap:wrap; margin-bottom:14px; }
    .case-meta span { display:flex; align-items:center; gap:4px; }

    .progress-wrap { margin-bottom:14px; }
    .progress-bar { height:6px; background:#f0f0f0; border-radius:10px; overflow:hidden; }
    .progress-fill { height:100%; background:linear-gradient(90deg,var(--gold-main),var(--gold-dark)); border-radius:10px; transition:0.5s; }
    .progress-label { display:flex; justify-content:space-between; font-size:0.75rem; color:#888; margin-top:4px; }

    .case-footer { display:flex; justify-content:space-between; align-items:center; }
    .badge { padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; }
    .badge-active  { background:#d1fae5; color:#065f46; }
    .badge-on_hold { background:#fef3c7; color:#b45309; }
    .badge-closed  { background:#f1f5f9; color:#64748b; }
    .badge-won     { background:#d1fae5; color:#065f46; }
    .badge-lost    { background:#fee2e2; color:#b91c1c; }

    .btn-sm { padding:7px 14px; background:var(--navy); color:#fff; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:0.2s; }
    .btn-sm:hover { background:var(--gold-main); color:var(--navy); }

    .empty-state { text-align:center; padding:70px 20px; color:#aaa; background:#fff; border-radius:14px; }
    .empty-state i { font-size:3rem; display:block; margin-bottom:15px; opacity:0.4; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2><i class="fas fa-briefcase" style="color:var(--gold-main);margin-left:10px;"></i>پرونده‌های حقوقی</h2>
    <a href="{{ route('lawyer.cases.create') }}" class="btn-new">
        <i class="fas fa-plus"></i> پرونده جدید
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-bottom:3px solid #10b981;">
        <span class="stat-n">{{ $stats['active'] }}</span><span class="stat-l">فعال</span>
    </div>
    <div class="stat-card" style="border-bottom:3px solid #f59e0b;">
        <span class="stat-n">{{ $stats['on_hold'] }}</span><span class="stat-l">معلق</span>
    </div>
    <div class="stat-card" style="border-bottom:3px solid #64748b;">
        <span class="stat-n">{{ $stats['closed'] }}</span><span class="stat-l">بسته</span>
    </div>
    <div class="stat-card" style="border-bottom:3px solid var(--gold-main);">
        <span class="stat-n">{{ $stats['total'] }}</span><span class="stat-l">کل</span>
    </div>
</div>

<form method="GET" class="filter-bar">
    <input type="text" name="search" placeholder="جستجو عنوان یا شماره پرونده..." value="{{ request('search') }}">
    <select name="status">
        <option value="">همه وضعیت‌ها</option>
        <option value="active"  @selected(request('status')==='active')>فعال</option>
        <option value="on_hold" @selected(request('status')==='on_hold')>معلق</option>
        <option value="closed"  @selected(request('status')==='closed')>بسته</option>
        <option value="won"     @selected(request('status')==='won')>برنده</option>
        <option value="lost"    @selected(request('status')==='lost')>بازنده</option>
    </select>
    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> جستجو</button>
</form>

@if($cases->isNotEmpty())
    <div class="cases-grid">
        @foreach($cases as $case)
            @php
                $statusLabel = ['active'=>'فعال','on_hold'=>'معلق','closed'=>'بسته','won'=>'برنده','lost'=>'بازنده'][$case->current_status] ?? $case->current_status;
                $statusClass = 'badge-'.$case->current_status;
            @endphp
            <div class="case-card {{ $case->current_status }}">
                <div class="case-num"># {{ $case->case_number }}</div>
                <div class="case-title">{{ $case->title }}</div>
                <div class="case-meta">
                    <span><i class="fas fa-user"></i> {{ $case->user->name ?? '—' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}</span>
                </div>
                <div class="progress-wrap">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $case->progress_percent }}%;"></div>
                    </div>
                    <div class="progress-label">
                        <span>پرداخت‌شده: {{ number_format($case->paid_amount) }} ت</span>
                        <span>{{ $case->progress_percent }}٪</span>
                    </div>
                </div>
                <div class="case-footer">
                    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    <a href="{{ route('lawyer.cases.show', $case) }}" class="btn-sm">
                        <i class="fas fa-eye"></i> مشاهده
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>هیچ پرونده‌ای یافت نشد.</p>
        <a href="{{ route('lawyer.cases.create') }}" class="btn-new" style="display:inline-flex;margin-top:15px;">
            <i class="fas fa-plus"></i> ایجاد اولین پرونده
        </a>
    </div>
@endif

@if($cases->hasPages())
    <div class="pagination-wrap">
        @if($cases->onFirstPage())
            <span class="page-btn disabled">قبلی</span>
        @else
            <a href="{{ $cases->previousPageUrl() }}" class="page-btn">قبلی</a>
        @endif
        @foreach($cases->getUrlRange(1,$cases->lastPage()) as $page => $url)
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