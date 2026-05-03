@extends('layouts.client')

@section('title', 'پرونده: '.$case->case_number)

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .case-hero {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        border-radius: 16px; padding: 28px 32px; color: #fff; margin-bottom: 25px;
    }
    .case-hero .case-num { font-size: 0.82rem; color: rgba(255,255,255,0.55); margin-bottom: 6px; }
    .case-hero h2 { font-size: 1.4rem; font-weight: 900; margin: 0 0 10px; }
    .case-hero-meta { display: flex; gap: 18px; flex-wrap: wrap; font-size: 0.82rem; color: rgba(255,255,255,0.65); }
    .case-hero-meta span { display: flex; align-items: center; gap: 5px; }
    .badge { padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .badge-active  { background: rgba(16,185,129,0.2); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.3); }
    .badge-on_hold { background: rgba(245,158,11,0.2); color: #fcd34d; border: 1px solid rgba(245,158,11,0.3); }
    .badge-closed, .badge-won, .badge-lost { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); }

    .grid-main { display: grid; grid-template-columns: 1fr 320px; gap: 25px; align-items: start; }

    .card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .card-title { font-size: 1rem; font-weight: 800; color: var(--navy); margin-bottom: 18px; padding-bottom: 12px; border-bottom: 2px solid #f5f0ea; display: flex; align-items: center; gap: 8px; }
    .card-title i { color: var(--gold-main); }

    /* مالی */
    .fin-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px; }
    .fin-item { text-align: center; padding: 14px; border-radius: 10px; background: #f8fafc; }
    .fin-item .n { font-size: 1.1rem; font-weight: 900; color: var(--navy); display: block; }
    .fin-item .l { font-size: 0.72rem; color: #888; margin-top: 3px; display: block; }
    .fin-item.green { background: #f0fdf4; }
    .fin-item.green .n { color: #059669; }
    .fin-item.amber { background: #fffbeb; }
    .fin-item.amber .n { color: #d97706; }
    .progress-bar { height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--gold-main), var(--gold-dark)); border-radius: 10px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 0.75rem; color: #888; margin-top: 5px; }

    /* تایم‌لاین */
    .timeline { padding-right: 24px; position: relative; }
    .timeline::before { content: ''; position: absolute; right: 8px; top: 0; bottom: 0; width: 2px; background: #f0f0f0; }
    .tl-item { position: relative; margin-bottom: 24px; }
    .tl-dot { position: absolute; right: -24px; top: 4px; width: 16px; height: 16px; border-radius: 50%; background: var(--gold-main); border: 3px solid #fff; box-shadow: 0 0 0 2px rgba(197,160,89,0.25); }
    .tl-date { font-size: 0.72rem; color: #888; margin-bottom: 4px; }
    .tl-title { font-size: 0.95rem; font-weight: 800; color: var(--navy); margin-bottom: 5px; }
    .tl-body { font-size: 0.85rem; color: #666; line-height: 1.7; }
    .tl-docs { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .tl-doc {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f8fafc; border: 1px solid #e0e0e0; border-radius: 8px;
        padding: 5px 10px; font-size: 0.78rem; color: var(--navy); text-decoration: none; transition: 0.2s;
    }
    .tl-doc:hover { border-color: var(--gold-main); }

    /* اقساط */
    .inst-table { width: 100%; border-collapse: collapse; }
    .inst-table th { background: #f8fafc; padding: 10px 14px; text-align: right; font-size: 0.8rem; color: #64748b; font-weight: 700; border-bottom: 1px solid #f0f0f0; }
    .inst-table td { padding: 12px 14px; border-bottom: 1px solid #f8f8f8; font-size: 0.85rem; vertical-align: middle; }
    .inst-table tr:last-child td { border-bottom: none; }
    .badge-paid    { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-pending { background: #fef3c7; color: #b45309; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .badge-overdue { background: #fee2e2; color: #b91c1c; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }

    .btn-pay { padding: 6px 14px; background: var(--gold-main); color: #fff; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; }
    .btn-pay:hover { background: var(--gold-dark); color: #fff; }

    /* سایدبار */
    .lawyer-box { background: linear-gradient(135deg, var(--navy), #1e3a5f); border-radius: 14px; padding: 22px; color: #fff; text-align: center; margin-bottom: 18px; }
    .lawyer-avatar { width: 60px; height: 60px; border-radius: 50%; background: rgba(212,175,55,0.2); border: 2px solid rgba(212,175,55,0.5); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 900; color: var(--gold-main); margin: 0 auto 10px; }
    .lawyer-box h4 { font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
    .lawyer-box p { color: rgba(255,255,255,0.6); font-size: 0.8rem; }

    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.88rem; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #888; }
    .info-value { font-weight: 700; color: var(--navy); }

    .btn-chat { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px; background: var(--navy); color: #fff; border-radius: 9px; font-weight: 700; font-size: 0.88rem; text-decoration: none; transition: 0.2s; }
    .btn-chat:hover { background: var(--gold-main); color: #fff; }

    /* اسناد */
    .doc-item {
        display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc;
        border-radius: 10px; border: 1px solid #e0e0e0; margin-bottom: 8px; transition: 0.2s; text-decoration: none; color: var(--navy);
    }
    .doc-item:hover { border-color: var(--gold-main); }
    .doc-icon { width: 40px; height: 40px; border-radius: 8px; background: rgba(197,160,89,0.1); color: var(--gold-main); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .doc-info h4 { font-size: 0.85rem; font-weight: 700; margin: 0 0 3px; }
    .doc-info p { font-size: 0.72rem; color: #888; margin: 0; }

    @media (max-width: 960px) { .grid-main { grid-template-columns: 1fr; } .fin-grid { grid-template-columns: 1fr 1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('client.cases.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به پرونده‌ها
</a>

@php
    $statusLabel = ['active'=>'فعال','on_hold'=>'معلق','closed'=>'بسته','won'=>'برنده','lost'=>'بازنده'][$case->current_status] ?? $case->current_status;
@endphp

<div class="case-hero">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;">
        <div>
            <div class="case-num"># {{ $case->case_number }}</div>
            <h2>{{ $case->title }}</h2>
            <div class="case-hero-meta">
                <span><i class="fas fa-user-tie"></i> {{ $case->lawyer->name ?? '—' }}</span>
                @if($case->service)
                    <span><i class="fas fa-tag"></i> {{ $case->service->title }}</span>
                @endif
                <span><i class="fas fa-calendar-alt"></i> افتتاح: {{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}</span>
            </div>
        </div>
        <span class="badge badge-{{ $case->current_status }}">{{ $statusLabel }}</span>
    </div>
</div>

<div class="grid-main">

    <div>
        {{-- اطلاعات مالی --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-wallet"></i> اطلاعات مالی</div>
            <div class="fin-grid">
                <div class="fin-item">
                    <span class="n">{{ number_format($case->total_fee) }}</span>
                    <span class="l">حق‌الوکاله کل (ت)</span>
                </div>
                <div class="fin-item green">
                    <span class="n">{{ number_format($case->paid_amount) }}</span>
                    <span class="l">پرداخت‌شده (ت)</span>
                </div>
                <div class="fin-item amber">
                    <span class="n">{{ number_format(max(0, $case->total_fee - $case->paid_amount)) }}</span>
                    <span class="l">باقی‌مانده (ت)</span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $case->progress_percent }}%;"></div>
            </div>
            <div class="progress-label">
                <span>پیشرفت پرداخت</span>
                <span>{{ $case->progress_percent }}٪</span>
            </div>
        </div>

        {{-- تاریخچه پرونده --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-history"></i> تاریخچه و وضعیت پرونده</div>
            @if($case->statusLogs->isNotEmpty())
                <div class="timeline">
                    @foreach($case->statusLogs as $log)
                        <div class="tl-item">
                            <div class="tl-dot"></div>
                            <div class="tl-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($log->status_date)->format('Y/m/d') }}</div>
                            <div class="tl-title">{{ $log->status_title }}</div>
                            @if($log->description)
                                <div class="tl-body">{{ $log->description }}</div>
                            @endif
                            @if($log->documents->isNotEmpty())
                                <div class="tl-docs">
                                    @foreach($log->documents as $doc)
                                        <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="tl-doc">
                                            <i class="fas fa-file"></i> {{ $doc->title }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:30px;color:#aaa;">
                    <i class="fas fa-clock" style="font-size:2rem;display:block;margin-bottom:10px;opacity:0.3;"></i>
                    <p style="font-size:0.85rem;">هنوز وضعیتی توسط وکیل ثبت نشده است.</p>
                </div>
            @endif
        </div>

        {{-- اقساط --}}
        <div class="card">
            <div class="card-title"><i class="fas fa-receipt"></i> اقساط پرونده</div>
            @if($case->installments->isNotEmpty())
                <table class="inst-table">
                    <thead>
                        <tr>
                            <th>شماره</th>
                            <th>مبلغ (تومان)</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->installments as $inst)
                            <tr>
                                <td style="font-weight:700;color:var(--navy);">{{ $inst->installment_number }}</td>
                                <td style="font-weight:700;">{{ number_format($inst->amount) }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}</td>
                                <td>
                                    @if($inst->status === 'paid')
                                        <span class="badge-paid">پرداخت‌شده</span>
                                    @elseif($inst->due_date < now())
                                        <span class="badge-overdue">سررسیدگذشته</span>
                                    @else
                                        <span class="badge-pending">در انتظار</span>
                                    @endif
                                </td>
                                <td>
                                    @if($inst->status !== 'paid')
                                        <a href="{{ route('client.installments.pay', $inst) }}" class="btn-pay">
                                            <i class="fas fa-credit-card"></i> پرداخت
                                        </a>
                                    @else
                                        <span style="font-size:0.78rem;color:#888;">{{ $inst->paid_at ? \Morilog\Jalali\Jalalian::fromCarbon($inst->paid_at)->format('Y/m/d') : '—' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($nextInstallment)
                    <div style="margin-top:14px;padding:12px 15px;background:rgba(197,160,89,0.08);border-radius:8px;border-right:3px solid var(--gold-main);font-size:0.85rem;">
                        <strong>قسط بعدی:</strong>
                        {{ number_format($nextInstallment->amount) }} تومان — سررسید {{ \Morilog\Jalali\Jalalian::fromCarbon($nextInstallment->due_date)->format('Y/m/d') }}
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:25px;color:#aaa;">
                    <p style="font-size:0.85rem;">هنوز قسطی تعریف نشده است.</p>
                </div>
            @endif
        </div>

        {{-- اسناد --}}
        @if($visibleDocuments->isNotEmpty())
            <div class="card">
                <div class="card-title"><i class="fas fa-folder-open"></i> اسناد و مدارک پرونده</div>
                @foreach($visibleDocuments as $doc)
                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="doc-item">
                        <div class="doc-icon">
                            <i class="fas fa-{{ in_array($doc->file_type, ['jpg','jpeg','png']) ? 'image' : 'file-pdf' }}"></i>
                        </div>
                        <div class="doc-info">
                            <h4>{{ $doc->title }}</h4>
                            <p>{{ strtoupper($doc->file_type) }} &nbsp;·&nbsp; {{ number_format($doc->file_size / 1024) }} KB</p>
                        </div>
                        <i class="fas fa-download" style="margin-right:auto;color:var(--gold-main);"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- سایدبار --}}
    <div>
        @if($case->lawyer)
            <div class="lawyer-box">
                <div class="lawyer-avatar">{{ mb_substr($case->lawyer->name, 0, 1) }}</div>
                <h4>{{ $case->lawyer->name }}</h4>
                <p>وکیل پایه {{ $case->lawyer->license_grade ?? 'یک' }} دادگستری</p>
            </div>
        @endif

        <div class="card">
            <div class="card-title"><i class="fas fa-info-circle"></i> اطلاعات پرونده</div>
            <div class="info-row">
                <span class="info-label">شماره پرونده</span>
                <span class="info-value"># {{ $case->case_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">وضعیت</span>
                <span class="info-value">{{ $statusLabel }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">خدمت</span>
                <span class="info-value">{{ $case->service->title ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاریخ افتتاح</span>
                <span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}</span>
            </div>
            @if($case->closed_at)
                <div class="info-row">
                    <span class="info-label">تاریخ بستن</span>
                    <span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($case->closed_at)->format('Y/m/d') }}</span>
                </div>
            @endif
        </div>

        @if($case->conversation)
            <div class="card">
                <div class="card-title"><i class="fas fa-comments"></i> ارتباط با وکیل</div>
                <a href="{{ route('client.chat.show', $case->conversation->id) }}" class="btn-chat">
                    <i class="fas fa-comment-dots"></i> ورود به اتاق گفتگو
                </a>
            </div>
        @endif

        <div class="card">
            <div class="card-title"><i class="fas fa-bolt"></i> عملیات سریع</div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('client.installments.index') }}" style="display:flex;align-items:center;gap:8px;padding:11px;background:#f8fafc;border-radius:9px;font-size:0.85rem;font-weight:700;color:var(--navy);text-decoration:none;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                    <i class="fas fa-receipt" style="color:var(--gold-main);"></i> مشاهده همه اقساط
                </a>
                <a href="{{ route('client.cases.index') }}" style="display:flex;align-items:center;gap:8px;padding:11px;background:#f8fafc;border-radius:9px;font-size:0.85rem;font-weight:700;color:var(--navy);text-decoration:none;transition:0.2s;" onmouseover="this.style.background='var(--navy)';this.style.color='#fff';" onmouseout="this.style.background='#f8fafc';this.style.color='var(--navy)';">
                    <i class="fas fa-briefcase" style="color:var(--gold-main);"></i> همه پرونده‌ها
                </a>
            </div>
        </div>
    </div>

</div>

@endsection