@extends('layouts.lawyer')
@section('title', 'پرونده: '.$case->case_number)

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .case-header { background:linear-gradient(135deg,var(--navy),#1e3a5f); border-radius:16px; padding:28px 32px; color:#fff; margin-bottom:25px; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; }
    .case-header h2 { font-size:1.3rem; font-weight:900; margin:0 0 6px; }
    .case-header .case-num { font-size:0.85rem; color:rgba(255,255,255,0.6); }
    .header-actions { display:flex; gap:10px; flex-wrap:wrap; }
    .btn-edit { padding:9px 18px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; border-radius:9px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:0.2s; }
    .btn-edit:hover { background:rgba(255,255,255,0.2); color:#fff; }
    .badge { padding:5px 14px; border-radius:20px; font-size:0.78rem; font-weight:700; }
    .badge-active  { background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.3); }
    .badge-on_hold { background:rgba(245,158,11,0.2); color:#fcd34d; border:1px solid rgba(245,158,11,0.3); }
    .badge-closed, .badge-won, .badge-lost { background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.7); border:1px solid rgba(255,255,255,0.2); }

    .grid-main { display:grid; grid-template-columns:1fr 320px; gap:25px; align-items:start; }

    .card { background:#fff; border-radius:14px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:18px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; justify-content:space-between; }
    .card-title .left { display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f5f5f5; font-size:0.88rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#888; }
    .info-value { font-weight:700; color:var(--navy); }

    /* نوار پیشرفت مالی */
    .finance-box { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:16px; }
    .finance-item { text-align:center; padding:14px; background:#f8fafc; border-radius:10px; }
    .finance-item .n { font-size:1.1rem; font-weight:900; color:var(--navy); display:block; }
    .finance-item .l { font-size:0.75rem; color:#888; margin-top:3px; display:block; }
    .progress-bar { height:8px; background:#f0f0f0; border-radius:10px; overflow:hidden; }
    .progress-fill { height:100%; background:linear-gradient(90deg,var(--gold-main),var(--gold-dark)); border-radius:10px; }

    /* لاگ وضعیت */
    .timeline { position:relative; padding-right:24px; }
    .timeline::before { content:''; position:absolute; right:8px; top:0; bottom:0; width:2px; background:#f0f0f0; }
    .tl-item { position:relative; margin-bottom:22px; }
    .tl-dot { position:absolute; right:-24px; top:4px; width:16px; height:16px; border-radius:50%; background:var(--gold-main); border:3px solid #fff; box-shadow:0 0 0 2px rgba(197,160,89,0.3); }
    .tl-date { font-size:0.75rem; color:#888; margin-bottom:4px; }
    .tl-title { font-size:0.95rem; font-weight:800; color:var(--navy); margin-bottom:5px; }
    .tl-body { font-size:0.85rem; color:#666; line-height:1.7; }
    .tl-docs { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
    .tl-doc { display:inline-flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #e0e0e0; border-radius:8px; padding:5px 10px; font-size:0.78rem; color:var(--navy); text-decoration:none; }
    .tl-doc:hover { border-color:var(--gold-main); }

    /* اقساط */
    .inst-table { width:100%; border-collapse:collapse; }
    .inst-table th { background:#f8fafc; padding:10px 14px; text-align:right; font-size:0.8rem; color:#64748b; font-weight:700; border-bottom:1px solid #f0f0f0; }
    .inst-table td { padding:11px 14px; border-bottom:1px solid #f8f8f8; font-size:0.85rem; }
    .inst-table tr:last-child td { border-bottom:none; }
    .badge-paid    { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }
    .badge-pending { background:#fef3c7; color:#b45309; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }
    .badge-overdue { background:#fee2e2; color:#b91c1c; padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }

    /* فرم ثبت وضعیت */
    .form-group { margin-bottom:16px; }
    .form-label { display:block; margin-bottom:7px; font-size:0.85rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:10px 14px; border:1.5px solid #e0e0e0; border-radius:9px; font-family:'Vazirmatn',sans-serif; font-size:0.9rem; outline:none; transition:0.2s; }
    .form-input:focus { border-color:var(--gold-main); }
    .btn-submit { padding:11px 22px; background:var(--navy); color:#fff; border:none; border-radius:9px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:0.2s; }
    .btn-submit:hover { background:var(--gold-main); color:var(--navy); }
    .btn-add-inst { padding:8px 16px; background:rgba(197,160,89,0.1); border:1.5px solid var(--gold-main); color:var(--gold-dark); border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.83rem; cursor:pointer; }

    /* سایدبار */
    .client-box { background:linear-gradient(135deg,var(--navy),#1e3a5f); border-radius:14px; padding:22px; color:#fff; text-align:center; margin-bottom:18px; }
    .client-avatar { width:58px; height:58px; border-radius:50%; background:rgba(212,175,55,0.2); border:2px solid rgba(212,175,55,0.5); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:900; color:var(--gold-main); margin:0 auto 10px; }
    .client-box h4 { font-size:1rem; font-weight:800; margin-bottom:4px; }
    .client-box p { color:rgba(255,255,255,0.6); font-size:0.8rem; }
    .btn-client-link { display:block; margin-top:12px; padding:9px; background:rgba(255,255,255,0.08); border-radius:8px; color:#fff; text-decoration:none; font-size:0.82rem; font-weight:600; transition:0.2s; }
    .btn-client-link:hover { background:rgba(255,255,255,0.15); color:#fff; }

    @media(max-width:960px) { .grid-main { grid-template-columns:1fr; } .finance-box { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.cases.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به پرونده‌ها
</a>

@php
    $statusLabel = ['active'=>'فعال','on_hold'=>'معلق','closed'=>'بسته','won'=>'برنده','lost'=>'بازنده'][$case->current_status] ?? $case->current_status;
@endphp

<div class="case-header">
    <div>
        <div class="case-num"># {{ $case->case_number }}</div>
        <h2>{{ $case->title }}</h2>
        <span class="badge badge-{{ $case->current_status }}">{{ $statusLabel }}</span>
    </div>
    <div class="header-actions">
        <a href="{{ route('lawyer.cases.edit', $case) }}" class="btn-edit">
            <i class="fas fa-edit"></i> ویرایش پرونده
        </a>
    </div>
</div>

<div class="grid-main">

    {{-- ستون اصلی --}}
    <div>
        {{-- اطلاعات مالی --}}
        <div class="card">
            <div class="card-title"><span class="left"><i class="fas fa-wallet"></i> اطلاعات مالی</span></div>
            <div class="finance-box">
                <div class="finance-item">
                    <span class="n">{{ number_format($case->total_fee) }}</span>
                    <span class="l">کل حق‌الوکاله (ت)</span>
                </div>
                <div class="finance-item" style="background:#d1fae5;">
                    <span class="n" style="color:#065f46;">{{ number_format($case->paid_amount) }}</span>
                    <span class="l">پرداخت‌شده (ت)</span>
                </div>
                <div class="finance-item" style="background:#fef3c7;">
                    <span class="n" style="color:#b45309;">{{ number_format($case->remaining_fee) }}</span>
                    <span class="l">باقی‌مانده (ت)</span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $case->progress_percent }}%;"></div>
            </div>
            <div style="font-size:0.78rem;color:#888;margin-top:4px;text-align:left;">{{ $case->progress_percent }}٪ پرداخت شده</div>
        </div>

        {{-- نوار پیشرفت پرونده --}}
        <div class="card">
            <div class="card-title">
                <span class="left"><i class="fas fa-history"></i> تاریخچه پرونده</span>
            </div>
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
                <p style="color:#aaa;text-align:center;padding:20px 0;">هنوز وضعیتی ثبت نشده است.</p>
            @endif

            {{-- فرم ثبت وضعیت جدید --}}
            <div style="border-top:2px solid #f5f0ea;margin-top:20px;padding-top:20px;">
                <div style="font-size:0.92rem;font-weight:800;color:var(--navy);margin-bottom:16px;">
                    <i class="fas fa-plus-circle" style="color:var(--gold-main);margin-left:6px;"></i>ثبت وضعیت جدید
                </div>
                <form method="POST" action="{{ route('lawyer.cases.status-log', $case) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label class="form-label">عنوان وضعیت</label>
                            <input type="text" name="status_title" class="form-input" placeholder="مثال: تقدیم دادخواست" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تاریخ</label>
                            <input type="date" name="status_date" class="form-input" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">توضیح (نمایش به موکل)</label>
                        <textarea name="description" class="form-input" rows="3" placeholder="توضیح کوتاه از آنچه اتفاق افتاد..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">یادداشت خصوصی (فقط برای شما)</label>
                        <textarea name="private_notes" class="form-input" rows="2" placeholder="یادداشت محرمانه..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">اسناد ضمیمه</label>
                        <input type="file" name="documents[]" class="form-input" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus"></i> ثبت وضعیت
                    </button>
                </form>
            </div>
        </div>

        {{-- اقساط --}}
        <div class="card">
            <div class="card-title">
                <span class="left"><i class="fas fa-money-bill-wave"></i> اقساط پرونده</span>
            </div>
            @if($case->installments->isNotEmpty())
                <table class="inst-table">
                    <thead>
                        <tr>
                            <th>شماره</th>
                            <th>مبلغ (ت)</th>
                            <th>سررسید</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($case->installments as $inst)
                            <tr>
                                <td>{{ $inst->installment_number }}</td>
                                <td style="font-weight:700;">{{ number_format($inst->amount) }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}</td>
                                <td>
                                    @if($inst->status === 'paid')
                                        <span class="badge-paid">پرداخت‌شده</span>
                                    @elseif($inst->isOverdue())
                                        <span class="badge-overdue">سررسید گذشته</span>
                                    @else
                                        <span class="badge-pending">در انتظار</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#aaa;text-align:center;padding:20px 0;">هنوز قسطی ثبت نشده.</p>
            @endif

            {{-- فرم ثبت اقساط --}}
            <div style="border-top:2px solid #f5f0ea;margin-top:20px;padding-top:20px;">
                <div style="font-size:0.92rem;font-weight:800;color:var(--navy);margin-bottom:16px;">
                    <i class="fas fa-plus-circle" style="color:var(--gold-main);margin-left:6px;"></i>تعریف جدول اقساط
                </div>
                <form method="POST" action="{{ route('lawyer.cases.installments', $case) }}">
                    @csrf
                    <div id="installmentRows">
                        <div class="inst-row" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                            <input type="number" name="installments[0][amount]" class="form-input" placeholder="مبلغ (تومان)" min="1000" required>
                            <input type="date" name="installments[0][due_date]" class="form-input" required>
                        </div>
                    </div>
                    <button type="button" class="btn-add-inst" onclick="addRow()">
                        <i class="fas fa-plus"></i> افزودن قسط
                    </button>
                    <button type="submit" class="btn-submit" style="margin-right:10px;">
                        <i class="fas fa-save"></i> ذخیره اقساط
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- سایدبار --}}
    <div>
        {{-- موکل --}}
        <div class="client-box">
            <div class="client-avatar">{{ mb_substr($case->user->name ?? 'م', 0, 1) }}</div>
            <h4>{{ $case->user->name ?? 'موکل' }}</h4>
            <p>{{ $case->user->phone ?? '' }}</p>
            @if($case->user)
                <a href="{{ route('lawyer.clients.show', $case->user) }}" class="btn-client-link">
                    <i class="fas fa-user-tie"></i> پروفایل موکل
                </a>
            @endif
        </div>

        {{-- اطلاعات پرونده --}}
        <div class="card">
            <div class="card-title"><span class="left"><i class="fas fa-info-circle"></i> اطلاعات</span></div>
            <div class="info-row"><span class="info-label">خدمت</span><span class="info-value">{{ $case->service->title ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">تاریخ افتتاح</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at ?? $case->created_at)->format('Y/m/d') }}</span></div>
            @if($case->closed_at)
                <div class="info-row"><span class="info-label">تاریخ بستن</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($case->closed_at)->format('Y/m/d') }}</span></div>
            @endif
        </div>

        {{-- چت --}}
        @if($case->conversation)
            <div class="card">
                <div class="card-title"><span class="left"><i class="fas fa-comments"></i> چت پرونده</span></div>
                <a href="{{ route('lawyer.chat.show', $case->conversation->id) }}"
                   style="display:block;text-align:center;padding:12px;background:var(--navy);color:#fff;border-radius:9px;font-weight:700;font-size:0.88rem;text-decoration:none;">
                    <i class="fas fa-comment-dots"></i> مشاهده گفتگو
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
let rowCount = 1;
function addRow() {
    const div = document.createElement('div');
    div.className = 'inst-row';
    div.style.cssText = 'display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;';
    div.innerHTML = `
        <input type="number" name="installments[${rowCount}][amount]" class="form-input" placeholder="مبلغ (تومان)" min="1000" required style="padding:10px 14px;border:1.5px solid #e0e0e0;border-radius:9px;font-family:'Vazirmatn',sans-serif;font-size:0.9rem;outline:none;">
        <input type="date" name="installments[${rowCount}][due_date]" class="form-input" required style="padding:10px 14px;border:1.5px solid #e0e0e0;border-radius:9px;font-family:'Vazirmatn',sans-serif;font-size:0.9rem;outline:none;">
    `;
    document.getElementById('installmentRows').appendChild(div);
    rowCount++;
}
</script>
@endpush

@endsection