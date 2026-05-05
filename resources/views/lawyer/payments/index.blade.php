@extends('layouts.lawyer')
@section('title', 'پرداخت‌ها')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; border-bottom:3px solid transparent; transition:0.3s; }
    .stat-card:hover { transform:translateY(-3px); }
    .stat-n { font-size:1.5rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar { background:#fff; padding:18px 22px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:9px 14px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; outline:none; transition:0.2s; }
    .filter-bar input { flex:1; min-width:180px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--gold-main); }
    .btn-filter { background:var(--navy); color:#fff; padding:9px 18px; border:none; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; display:flex; align-items:center; gap:6px; }

    .table-box { background:#fff; border-radius:14px; box-shadow:0 4px 15px rgba(0,0,0,0.05); overflow:hidden; }
    .data-table { width:100%; border-collapse:collapse; }
    .data-table th { background:#f8fafc; padding:13px 18px; text-align:right; font-size:0.82rem; color:#64748b; font-weight:700; border-bottom:1px solid #f0f0f0; }
    .data-table td { padding:14px 18px; border-bottom:1px solid #f8f8f8; font-size:0.88rem; color:#374151; vertical-align:middle; }
    .data-table tr:last-child td { border-bottom:none; }
    .data-table tr:hover td { background:#fdfbf7; }

    .badge { padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:700; }
    .badge-paid    { background:#d1fae5; color:#065f46; }
    .badge-pending { background:#fef3c7; color:#b45309; }
    .badge-failed  { background:#fee2e2; color:#b91c1c; }

    .type-tag { font-size:0.78rem; color:#666; display:flex; align-items:center; gap:5px; }

    .btn-sm { padding:6px 13px; border-radius:7px; font-size:0.78rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:0.2s; background:#f1f5f9; color:var(--navy); }
    .btn-sm:hover { background:var(--navy); color:#fff; }

    .amount { font-weight:800; color:var(--gold-dark); }
    .ref-code { font-size:0.75rem; color:#888; font-family:monospace; }

    .empty-state { text-align:center; padding:60px 20px; color:#aaa; }
    .empty-state i { font-size:3rem; display:block; margin-bottom:15px; opacity:0.4; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2><i class="fas fa-credit-card" style="color:var(--gold-main);margin-left:10px;"></i>پرداخت‌ها</h2>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-bottom-color:#10b981;">
        <span class="stat-n">{{ number_format($stats['total_paid'] / 1000000, 1) }}M</span>
        <span class="stat-l">کل دریافتی (تومان)</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#f59e0b;">
        <span class="stat-n">{{ number_format($stats['total_pending'] / 1000000, 1) }}M</span>
        <span class="stat-l">در انتظار (تومان)</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#3b82f6;">
        <span class="stat-n">{{ $stats['count_paid'] }}</span>
        <span class="stat-l">تعداد پرداخت موفق</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#94a3b8;">
        <span class="stat-n">{{ $stats['count_pending'] }}</span>
        <span class="stat-l">تعداد در انتظار</span>
    </div>
</div>

<form method="GET" class="filter-bar">
    <input type="text" name="search" placeholder="جستجو کد رهگیری یا نام موکل..." value="{{ request('search') }}">
    <select name="status">
        <option value="">همه وضعیت‌ها</option>
        <option value="paid"    @selected(request('status')==='paid')>پرداخت موفق</option>
        <option value="pending" @selected(request('status')==='pending')>در انتظار</option>
        <option value="failed"  @selected(request('status')==='failed')>ناموفق</option>
    </select>
    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> جستجو</button>
</form>

<div class="table-box">
    <table class="data-table">
        <thead>
            <tr>
                <th>موکل</th>
                <th>نوع</th>
                <th>مبلغ</th>
                <th>کد رهگیری</th>
                <th>تاریخ</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                @php
                    $isConsultation = $payment->payable_type && str_contains($payment->payable_type, 'Consultation');
                    $typeLabel = $isConsultation ? 'مشاوره' : 'قسط پرونده';
                    $typeIcon  = $isConsultation ? 'fa-headset' : 'fa-file-invoice-dollar';
                    $statusMap = [
                        'paid'    => ['l'=>'موفق',      'c'=>'badge-paid'],
                        'pending' => ['l'=>'در انتظار', 'c'=>'badge-pending'],
                        'failed'  => ['l'=>'ناموفق',    'c'=>'badge-failed'],
                    ];
                    $s = $statusMap[$payment->status] ?? ['l'=>$payment->status,'c'=>''];
                @endphp
                <tr>
                    <td>
                        <strong style="color:var(--navy);">{{ $payment->user->name ?? '—' }}</strong>
                        <div style="font-size:0.75rem;color:#888;">{{ $payment->user->phone ?? '' }}</div>
                    </td>
                    <td>
                        <span class="type-tag">
                            <i class="fas {{ $typeIcon }}" style="color:var(--gold-main);"></i>
                            {{ $typeLabel }}
                        </span>
                    </td>
                    <td><span class="amount">{{ number_format($payment->amount) }} ت</span></td>
                    <td><span class="ref-code">{{ $payment->tracking_code }}</span></td>
                    <td>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($payment->created_at)->format('Y/m/d') }}
                        @if($payment->paid_at)
                            <div style="font-size:0.72rem;color:#10b981;">
                                پرداخت: {{ \Morilog\Jalali\Jalalian::fromCarbon($payment->paid_at)->format('Y/m/d') }}
                            </div>
                        @endif
                    </td>
                    <td><span class="badge {{ $s['c'] }}">{{ $s['l'] }}</span></td>
                    <td>
                        <a href="{{ route('lawyer.payments.show', $payment) }}" class="btn-sm">
                            <i class="fas fa-eye"></i> جزئیات
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <p>هیچ پرداختی یافت نشد.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($payments->hasPages())
    <div class="pagination-wrap">
        @if($payments->onFirstPage())
            <span class="page-btn disabled">قبلی</span>
        @else
            <a href="{{ $payments->previousPageUrl() }}" class="page-btn">قبلی</a>
        @endif
        @foreach($payments->getUrlRange(1,$payments->lastPage()) as $page => $url)
            @if($page == $payments->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach
        @if($payments->hasMorePages())
            <a href="{{ $payments->nextPageUrl() }}" class="page-btn">بعدی</a>
        @else
            <span class="page-btn disabled">بعدی</span>
        @endif
    </div>
@endif

@endsection