@extends('layouts.lawyer')
@section('title', 'موکلین')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; }
    .stat-n { font-size:2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar { background:#fff; padding:18px 22px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:9px 14px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; outline:none; transition:0.2s; }
    .filter-bar input { flex:1; min-width:180px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--gold-main); }
    .btn-filter { background:var(--navy); color:#fff; padding:9px 18px; border:none; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:6px; }

    .tab-btns { display:flex; gap:8px; margin-bottom:20px; }
    .tab-btn { padding:8px 20px; border-radius:8px; border:1.5px solid #e0e0e0; background:#fff; font-family:'Vazirmatn',sans-serif; font-size:0.85rem; font-weight:600; color:#888; cursor:pointer; text-decoration:none; transition:0.2s; }
    .tab-btn.active, .tab-btn:hover { background:var(--navy); border-color:var(--navy); color:#fff; }

    .clients-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:18px; }
    .client-card { background:#fff; border-radius:14px; padding:22px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border:1px solid #f0f0f0; transition:0.3s; }
    .client-card:hover { transform:translateY(-3px); border-color:var(--gold-main); }

    .cc-header { display:flex; align-items:center; gap:14px; margin-bottom:16px; }
    .cc-avatar { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:var(--gold-main); display:flex; align-items:center; justify-content:center; font-size:1.3rem; font-weight:900; flex-shrink:0; }
    .cc-info h4 { font-size:0.95rem; font-weight:800; color:var(--navy); margin:0 0 4px; }
    .cc-info p { font-size:0.78rem; color:#888; margin:0; }

    .badge { padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }
    .badge-special { background:rgba(212,175,55,0.15); color:var(--gold-dark); border:1px solid rgba(212,175,55,0.3); }
    .badge-simple { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }

    .cc-stats { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px; }
    .cc-stat { background:#f8fafc; padding:10px; border-radius:8px; text-align:center; }
    .cc-stat .n { font-size:1.1rem; font-weight:800; color:var(--navy); display:block; }
    .cc-stat .l { font-size:0.7rem; color:#888; }

    .cc-footer { display:flex; gap:8px; }
    .btn-sm { padding:7px 14px; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:0.2s; flex:1; justify-content:center; }
    .btn-view { background:var(--navy); color:#fff; }
    .btn-view:hover { background:var(--gold-main); color:var(--navy); }
    .btn-upgrade { background:rgba(212,175,55,0.1); border:1.5px solid var(--gold-main); color:var(--gold-dark); font-family:'Vazirmatn',sans-serif; cursor:pointer; }
    .btn-upgrade:hover { background:var(--gold-main); color:#fff; }

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
    <h2><i class="fas fa-users" style="color:var(--gold-main);margin-left:10px;"></i>موکلین</h2>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-bottom:3px solid var(--gold-main);">
        <span class="stat-n">{{ $stats['special_count'] }}</span>
        <span class="stat-l">موکل ویژه</span>
    </div>
    <div class="stat-card" style="border-bottom:3px solid #64748b;">
        <span class="stat-n">{{ $stats['simple_count'] }}</span>
        <span class="stat-l">موکل ساده</span>
    </div>
</div>

<form method="GET" class="filter-bar">
    <input type="hidden" name="type" value="{{ request('type', 'all') }}">
    <input type="text" name="search" placeholder="جستجو بر اساس نام یا شماره..." value="{{ request('search') }}">
    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> جستجو</button>
</form>

<div class="tab-btns">
    <a href="{{ request()->fullUrlWithQuery(['type'=>'all']) }}" class="tab-btn {{ $type==='all' ? 'active' : '' }}">همه</a>
    <a href="{{ request()->fullUrlWithQuery(['type'=>'special']) }}" class="tab-btn {{ $type==='special' ? 'active' : '' }}">
        <i class="fas fa-crown" style="font-size:0.75rem;color:var(--gold-main);"></i> موکلین ویژه
    </a>
    <a href="{{ request()->fullUrlWithQuery(['type'=>'simple']) }}" class="tab-btn {{ $type==='simple' ? 'active' : '' }}">موکلین ساده</a>
</div>

@if($clients->isNotEmpty())
    <div class="clients-grid">
        @foreach($clients as $client)
            <div class="client-card">
                <div class="cc-header">
                    <div class="cc-avatar">{{ mb_substr($client->name, 0, 1) }}</div>
                    <div class="cc-info">
                        <h4>{{ $client->name }}</h4>
                        <p>{{ $client->phone }}</p>
                    </div>
                    <span class="badge {{ $client->isSpecial() ? 'badge-special' : 'badge-simple' }}">
                        {{ $client->isSpecial() ? 'ویژه' : 'ساده' }}
                    </span>
                </div>

                <div class="cc-stats">
                    <div class="cc-stat">
                        <span class="n">{{ $client->cases()->where('lawyer_id', auth('lawyer')->id())->count() }}</span>
                        <span class="l">پرونده</span>
                    </div>
                    <div class="cc-stat">
                        <span class="n">{{ $client->consultations()->where('lawyer_id', auth('lawyer')->id())->count() }}</span>
                        <span class="l">مشاوره</span>
                    </div>
                </div>

                <div style="font-size:0.75rem;color:#888;margin-bottom:14px;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-clock" style="color:var(--gold-main);"></i>
                    عضویت: {{ \Morilog\Jalali\Jalalian::fromCarbon($client->created_at)->format('Y/m/d') }}
                </div>

                <div class="cc-footer">
                    <a href="{{ route('lawyer.clients.show', $client) }}" class="btn-sm btn-view">
                        <i class="fas fa-eye"></i> مشاهده
                    </a>
                    @if($client->isSimple())
                        <form method="POST" action="{{ route('lawyer.clients.upgrade', $client) }}" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-sm btn-upgrade" style="width:100%;">
                                <i class="fas fa-crown" style="font-size:0.75rem;"></i> ارتقا به ویژه
                            </button>
                        </form>
                    @endif
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
        @foreach($clients->getUrlRange(1,$clients->lastPage()) as $page => $url)
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