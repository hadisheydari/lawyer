@extends('layouts.lawyer')
@section('title', 'موکلین')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:12px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; border-bottom:3px solid transparent; }
    .stat-n { font-size:2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar {
        background:#fff; padding:15px 20px; border-radius:12px;
        box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px;
        display:flex; gap:10px; flex-wrap:wrap; align-items:center;
    }
    .filter-tab {
        padding:7px 18px; border-radius:20px; border:1.5px solid #e0e0e0;
        background:#fff; font-family:'Vazirmatn',sans-serif; font-size:0.84rem;
        font-weight:600; color:#888; cursor:pointer; text-decoration:none; transition:0.2s;
    }
    .filter-tab:hover, .filter-tab.active { border-color:var(--navy); background:var(--navy); color:#fff; }

    .filter-bar input {
        flex:1; min-width:180px; padding:9px 14px; border:1.5px solid #e0e0e0;
        border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; outline:none;
    }
    .filter-bar input:focus { border-color:var(--gold-main); }
    .btn-filter {
        background:var(--navy); color:#fff; padding:9px 18px; border:none;
        border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700;
        font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:6px;
    }

    /* ─── Grid کارت‌ها ─── */
    .clients-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 18px;
    }
    .client-card {
        background: #fff; border-radius: 14px; padding: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;
        transition: 0.3s; display: flex; flex-direction: column; gap: 14px;
    }
    .client-card:hover { transform: translateY(-3px); border-color: var(--gold-main); }

    .cc-header { display: flex; align-items: center; gap: 14px; }
    .cc-avatar {
        width: 52px; height: 52px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: var(--gold-main); display: flex; align-items: center;
        justify-content: center; font-weight: 900; font-size: 1.2rem;
    }
    .cc-info h4 { font-size: 0.95rem; font-weight: 800; color: var(--navy); margin: 0 0 3px; }
    .cc-info p { font-size: 0.78rem; color: #888; margin: 0; }

    .cc-badges { display: flex; gap: 6px; flex-wrap: wrap; }
    .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-special { background: rgba(212,175,55,0.15); color: var(--gold-dark); border: 1px solid rgba(212,175,55,0.3); }
    .badge-simple  { background: #f1f5f9; color: #64748b; }
    .badge-active  { background: #d1fae5; color: #065f46; }
    .badge-blocked { background: #fee2e2; color: #b91c1c; }

    .cc-stats {
        display: grid; grid-template-columns: repeat(3,1fr); gap: 8px;
        background: #f8fafc; border-radius: 10px; padding: 12px;
    }
    .cs-item { text-align: center; }
    .cs-item .n { font-size: 1.1rem; font-weight: 800; color: var(--navy); display: block; }
    .cs-item .l { font-size: 0.68rem; color: #888; }

    .cc-footer { display: flex; justify-content: space-between; align-items: center; }
    .cc-date { font-size: 0.75rem; color: #aaa; display: flex; align-items: center; gap: 4px; }
    .btn-sm {
        padding: 7px 14px; background: var(--navy); color: #fff;
        border-radius: 8px; font-size: 0.8rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center;
        gap: 5px; transition: 0.2s;
    }
    .btn-sm:hover { background: var(--gold-main); color: var(--navy); }

    .empty-state { text-align:center; padding:70px 20px; color:#aaa; background:#fff; border-radius:14px; }
    .empty-state i { font-size:3rem; display:block; margin-bottom:15px; opacity:0.4; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }

    @media(max-width:600px) {
        .clients-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2><i class="fas fa-users" style="color:var(--gold-main);margin-left:10px;"></i>موکلین</h2>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-bottom-color:var(--gold-main);">
        <span class="stat-n">{{ $stats['special_count'] }}</span>
        <span class="stat-l">موکل ویژه</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#64748b;">
        <span class="stat-n">{{ $stats['simple_count'] }}</span>
        <span class="stat-l">مشتری عادی</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#3b82f6;">
        <span class="stat-n">{{ $stats['special_count'] + $stats['simple_count'] }}</span>
        <span class="stat-l">مجموع</span>
    </div>
</div>

{{-- فیلتر --}}
<div class="filter-bar">
    <a href="{{ route('lawyer.clients.index') }}" class="filter-tab {{ $type === 'all' ? 'active' : '' }}">همه</a>
    <a href="{{ route('lawyer.clients.index', ['type' => 'special']) }}" class="filter-tab {{ $type === 'special' ? 'active' : '' }}">
        <i class="fas fa-crown" style="color:var(--gold-main);font-size:0.75rem;"></i> موکل ویژه
    </a>
    <a href="{{ route('lawyer.clients.index', ['type' => 'simple']) }}" class="filter-tab {{ $type === 'simple' ? 'active' : '' }}">مشتری عادی</a>
    <form method="GET" action="{{ route('lawyer.clients.index') }}" style="display:flex;gap:8px;flex:1;min-width:220px;">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="text" name="search" placeholder="جستجو نام یا تلفن..." value="{{ request('search') }}">
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i></button>
    </form>
</div>

{{-- لیست موکلین --}}
@if($clients->isNotEmpty())
    <div class="clients-grid">
        @foreach($clients as $client)
            @php
                $caseCount = $client->cases->count();
                $consultCount = $client->consultations()->where('lawyer_id', auth('lawyer')->id())->count();
                $paidAmount = $client->cases->sum('paid_amount');
            @endphp
            <div class="client-card">
                <div class="cc-header">
                    <div class="cc-avatar">{{ mb_substr($client->name, 0, 1) }}</div>
                    <div class="cc-info">
                        <h4>{{ $client->name }}</h4>
                        <p><i class="fas fa-phone" style="font-size:0.65rem;"></i> {{ $client->phone }}</p>
                    </div>
                </div>

                <div class="cc-badges">
                    @if($client->isSpecial())
                        <span class="badge badge-special"><i class="fas fa-crown" style="font-size:0.65rem;"></i> موکل ویژه</span>
                    @else
                        <span class="badge badge-simple">مشتری عادی</span>
                    @endif
                    <span class="badge {{ $client->status === 'active' ? 'badge-active' : 'badge-blocked' }}">
                        {{ $client->status === 'active' ? 'فعال' : 'مسدود' }}
                    </span>
                </div>

                <div class="cc-stats">
                    <div class="cs-item">
                        <span class="n">{{ $caseCount }}</span>
                        <span class="l">پرونده</span>
                    </div>
                    <div class="cs-item">
                        <span class="n">{{ $consultCount }}</span>
                        <span class="l">مشاوره</span>
                    </div>
                    <div class="cs-item">
                        <span class="n">{{ number_format($paidAmount / 1000000, 0) }}M</span>
                        <span class="l">پرداختی</span>
                    </div>
                </div>

                <div class="cc-footer">
                    <span class="cc-date">
                        <i class="far fa-calendar-alt"></i>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($client->created_at)->format('Y/m/d') }}
                    </span>
                    <a href="{{ route('lawyer.clients.show', $client) }}" class="btn-sm">
                        <i class="fas fa-eye"></i> مشاهده
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <p>هیچ موکلی یافت نشد.</p>
    </div>
@endif

@if($clients->hasPages())
    <div class="pagination-wrap">
        @if($clients->onFirstPage())
            <span class="page-btn disabled">قبلی</span>
        @else
            <a href="{{ $clients->previousPageUrl() }}" class="page-btn">قبلی</a>
        @endif
        @foreach($clients->getUrlRange(1, $clients->lastPage()) as $page => $url)
            @if($page == $clients->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach
        @if($clients->hasMorePages())
            <a href="{{ $clients->nextPageUrl() }}" class="page-btn">بعدی</a>
        @else
            <span class="page-btn disabled">بعدی</span>
        @endif
    </div>
@endif

@endsection