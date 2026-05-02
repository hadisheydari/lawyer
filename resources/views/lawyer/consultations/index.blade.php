@extends('layouts.lawyer')
@section('title', 'مدیریت مشاوره‌ها')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; border-bottom:3px solid transparent; }
    .stat-card.pending  { border-color:#f59e0b; }
    .stat-card.confirmed{ border-color:#3b82f6; }
    .stat-card.completed{ border-color:#10b981; }
    .stat-card.cancelled{ border-color:#ef4444; }
    .stat-n { font-size:2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar {
        background:#fff; padding:18px 22px; border-radius:12px;
        box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px;
        display:flex; gap:12px; flex-wrap:wrap; align-items:center;
    }
    .filter-bar input, .filter-bar select {
        padding:9px 14px; border:1.5px solid #e0e0e0; border-radius:8px;
        font-family:'Vazirmatn',sans-serif; font-size:0.88rem; outline:none; transition:0.2s;
    }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--gold-main); }
    .filter-bar input { flex:1; min-width:180px; }
    .btn-filter {
        background:var(--navy); color:#fff; padding:9px 18px; border:none;
        border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700;
        font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:6px;
    }

    .table-box { background:#fff; border-radius:14px; box-shadow:0 4px 15px rgba(0,0,0,0.05); overflow:hidden; }
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th { background:#f8fafc; padding:13px 18px; text-align:right; font-size:0.82rem; color:#64748b; font-weight:700; border-bottom:1px solid #f0f0f0; }
    .data-table td { padding:14px 18px; border-bottom:1px solid #f8f8f8; font-size:0.88rem; color:#374151; vertical-align:middle; }
    .data-table tr:last-child td { border-bottom:none; }
    .data-table tr:hover td { background:#fdfbf7; }

    .badge { padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; }
    .badge-pending     { background:#fef3c7; color:#b45309; }
    .badge-confirmed   { background:#dbeafe; color:#1d4ed8; }
    .badge-in_progress { background:#ede9fe; color:#6d28d9; }
    .badge-completed   { background:#d1fae5; color:#065f46; }
    .badge-cancelled   { background:#fee2e2; color:#b91c1c; }
    .badge-rejected    { background:#fee2e2; color:#b91c1c; }

    .type-tag { font-size:0.78rem; color:#666; display:flex; align-items:center; gap:5px; }

    .action-btns { display:flex; align-items:center; gap:6px; }
    .btn-sm {
        padding:6px 13px; border-radius:7px; font-size:0.78rem; font-weight:700;
        text-decoration:none; cursor:pointer; border:none; font-family:'Vazirmatn',sans-serif;
        display:inline-flex; align-items:center; gap:5px; transition:0.2s;
    }
    .btn-view { background:#f1f5f9; color:var(--navy); }
    .btn-view:hover { background:var(--navy); color:#fff; }
    .btn-confirm { background:#dbeafe; color:#1d4ed8; }
    .btn-confirm:hover { background:#1d4ed8; color:#fff; }
    .btn-reject { background:#fee2e2; color:#b91c1c; }
    .btn-reject:hover { background:#b91c1c; color:#fff; }

    .empty-state { text-align:center; padding:60px 20px; color:#aaa; }
    .empty-state i { font-size:3rem; display:block; margin-bottom:15px; opacity:0.4; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn {
        padding:7px 13px; border-radius:8px; border:1px solid #ddd;
        color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s;
    }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2><i class="fas fa-headset" style="color:var(--gold-main);margin-left:10px;"></i>مدیریت مشاوره‌ها</h2>
</div>

{{-- آمار --}}
<div class="stats-grid">
    <div class="stat-card pending">
        <span class="stat-n">{{ $stats['pending'] }}</span>
        <span class="stat-l">در انتظار تأیید</span>
    </div>
    <div class="stat-card confirmed">
        <span class="stat-n">{{ $stats['confirmed'] }}</span>
        <span class="stat-l">تأیید شده</span>
    </div>
    <div class="stat-card completed">
        <span class="stat-n">{{ $stats['completed'] }}</span>
        <span class="stat-l">تکمیل شده</span>
    </div>
    <div class="stat-card cancelled">
        <span class="stat-n">{{ $stats['cancelled'] }}</span>
        <span class="stat-l">لغو/رد شده</span>
    </div>
</div>

{{-- فیلتر --}}
<form method="GET" class="filter-bar">
    <input type="text" name="search" placeholder="جستجو نام یا تلفن موکل..."
           value="{{ request('search') }}">
    <select name="status">
        <option value="">همه وضعیت‌ها</option>
        <option value="pending"   @selected(request('status')==='pending')>در انتظار</option>
        <option value="confirmed" @selected(request('status')==='confirmed')>تأیید شده</option>
        <option value="completed" @selected(request('status')==='completed')>تکمیل شده</option>
        <option value="cancelled" @selected(request('status')==='cancelled')>لغو شده</option>
        <option value="rejected"  @selected(request('status')==='rejected')>رد شده</option>
    </select>
    <select name="type">
        <option value="">همه انواع</option>
        <option value="appointment" @selected(request('type')==='appointment')>حضوری</option>
        <option value="call"        @selected(request('type')==='call')>تلفنی</option>
        <option value="chat"        @selected(request('type')==='chat')>چت</option>
    </select>
    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> جستجو</button>
</form>

{{-- جدول --}}
<div class="table-box">
    <table class="data-table">
        <thead>
            <tr>
                <th>موکل</th>
                <th>نوع</th>
                <th>تاریخ و ساعت</th>
                <th>مبلغ</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consultations as $c)
                @php
                    $typeMap = [
                        'appointment' => ['label'=>'حضوری',   'icon'=>'fa-calendar-check'],
                        'call'        => ['label'=>'تلفنی',   'icon'=>'fa-phone'],
                        'chat'        => ['label'=>'چت',      'icon'=>'fa-comment'],
                    ];
                    $t = $typeMap[$c->type] ?? ['label'=>$c->type, 'icon'=>'fa-file'];

                    $statusMap = [
                        'pending'     => ['label'=>'در انتظار','class'=>'badge-pending'],
                        'confirmed'   => ['label'=>'تأیید شده','class'=>'badge-confirmed'],
                        'in_progress' => ['label'=>'در جریان', 'class'=>'badge-in_progress'],
                        'completed'   => ['label'=>'تکمیل شده','class'=>'badge-completed'],
                        'cancelled'   => ['label'=>'لغو شده',  'class'=>'badge-cancelled'],
                        'rejected'    => ['label'=>'رد شده',   'class'=>'badge-rejected'],
                    ];
                    $s = $statusMap[$c->status] ?? ['label'=>$c->status,'class'=>''];
                @endphp
                <tr>
                    <td>
                        <strong style="color:var(--navy);">{{ $c->user->name ?? '—' }}</strong>
                        <div style="font-size:0.75rem;color:#888;">{{ $c->user->phone ?? '' }}</div>
                    </td>
                    <td>
                        <span class="type-tag">
                            <i class="fas {{ $t['icon'] }}" style="color:var(--gold-main);"></i>
                            {{ $t['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($c->scheduled_at)
                            {{ \Morilog\Jalali\Jalalian::fromCarbon($c->scheduled_at)->format('Y/m/d') }}
                            <div style="font-size:0.75rem;color:#888;">ساعت {{ $c->scheduled_at->format('H:i') }}</div>
                        @else
                            <span style="color:#bbb;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--gold-dark);">
                        {{ number_format($c->price) }} ت
                    </td>
                    <td><span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('lawyer.consultations.show', $c) }}" class="btn-sm btn-view">
                                <i class="fas fa-eye"></i> جزئیات
                            </a>
                            @if($c->isPending())
                                <form method="POST" action="{{ route('lawyer.consultations.confirm', $c) }}">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-confirm">
                                        <i class="fas fa-check"></i> تأیید
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-headset"></i>
                            <p>هیچ مشاوره‌ای یافت نشد.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($consultations->hasPages())
    <div class="pagination-wrap">
        @if($consultations->onFirstPage())
            <span class="page-btn disabled">قبلی</span>
        @else
            <a href="{{ $consultations->previousPageUrl() }}" class="page-btn">قبلی</a>
        @endif
        @foreach($consultations->getUrlRange(1,$consultations->lastPage()) as $page => $url)
            @if($page == $consultations->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach
        @if($consultations->hasMorePages())
            <a href="{{ $consultations->nextPageUrl() }}" class="page-btn">بعدی</a>
        @else
            <span class="page-btn disabled">بعدی</span>
        @endif
    </div>
@endif

@endsection