@extends('layouts.lawyer')
@section('title', 'جزئیات مشاوره')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .grid-2 { display:grid; grid-template-columns:1fr 320px; gap:25px; align-items:start; }

    .info-card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 15px rgba(0,0,0,0.05); }
    .card-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:20px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f5f5f5; font-size:0.9rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#888; }
    .info-value { font-weight:700; color:var(--navy); }

    .badge { padding:5px 14px; border-radius:20px; font-size:0.78rem; font-weight:700; }
    .badge-pending   { background:#fef3c7; color:#b45309; }
    .badge-confirmed { background:#dbeafe; color:#1d4ed8; }
    .badge-completed { background:#d1fae5; color:#065f46; }
    .badge-cancelled { background:#fee2e2; color:#b91c1c; }
    .badge-rejected  { background:#fee2e2; color:#b91c1c; }

    .client-box { background:linear-gradient(135deg,var(--navy),#1e3a5f); border-radius:14px; padding:22px; color:#fff; text-align:center; margin-bottom:20px; }
    .client-avatar { width:60px; height:60px; border-radius:50%; background:rgba(212,175,55,0.2); border:2px solid rgba(212,175,55,0.5); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:900; color:var(--gold-main); margin:0 auto 10px; }
    .client-box h4 { font-size:1rem; font-weight:800; margin-bottom:4px; }
    .client-box p { color:rgba(255,255,255,0.6); font-size:0.8rem; }

    .action-box { background:#fff; border-radius:14px; padding:22px; box-shadow:0 4px 15px rgba(0,0,0,0.05); }
    .action-btns { display:flex; flex-direction:column; gap:10px; }
    .btn-act {
        width:100%; padding:11px; border-radius:9px; font-family:'Vazirmatn',sans-serif;
        font-weight:700; font-size:0.88rem; cursor:pointer; border:none;
        display:flex; align-items:center; justify-content:center; gap:8px; transition:0.2s;
        text-decoration:none;
    }
    .btn-confirm-big { background:#dbeafe; color:#1d4ed8; }
    .btn-confirm-big:hover { background:#1d4ed8; color:#fff; }
    .btn-complete-big { background:#d1fae5; color:#065f46; }
    .btn-complete-big:hover { background:#065f46; color:#fff; }
    .btn-cancel-big { background:#fee2e2; color:#b91c1c; border:1.5px solid #fecaca; }
    .btn-cancel-big:hover { background:#fef2f2; }
    .btn-back { background:#f1f5f9; color:var(--navy); }
    .btn-back:hover { background:var(--navy); color:#fff; }

    .note-box { background:#fdfbf7; border-radius:10px; padding:16px; border-right:3px solid var(--gold-main); margin-top:18px; }
    .note-box p { font-size:0.88rem; color:#666; margin:0; line-height:1.8; }

    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal { background:#fff; border-radius:16px; padding:32px; width:100%; max-width:460px; }
    .modal h3 { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:18px; }
    .modal textarea { width:100%; padding:12px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.9rem; resize:vertical; min-height:100px; outline:none; }
    .modal textarea:focus { border-color:var(--gold-main); }
    .modal-btns { display:flex; gap:10px; margin-top:16px; justify-content:flex-end; }
    .btn-modal-cancel { padding:10px 18px; background:#f1f5f9; border:none; border-radius:8px; cursor:pointer; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; }
    .btn-modal-submit { padding:10px 18px; background:var(--navy); color:#fff; border:none; border-radius:8px; cursor:pointer; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.88rem; }

    @media(max-width:768px) { .grid-2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.consultations.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به لیست مشاوره‌ها
</a>

@php
    $statusMap = [
        'pending'     => ['label'=>'در انتظار تأیید','class'=>'badge-pending'],
        'confirmed'   => ['label'=>'تأیید شده',      'class'=>'badge-confirmed'],
        'in_progress' => ['label'=>'در جریان',        'class'=>'badge-confirmed'],
        'completed'   => ['label'=>'تکمیل شده',       'class'=>'badge-completed'],
        'cancelled'   => ['label'=>'لغو شده',         'class'=>'badge-cancelled'],
        'rejected'    => ['label'=>'رد شده',          'class'=>'badge-rejected'],
    ];
    $s = $statusMap[$consultation->status] ?? ['label'=>$consultation->status,'class'=>''];
    $typeLabels = ['chat'=>'چت متنی','call'=>'تماس تلفنی','appointment'=>'مشاوره حضوری'];
@endphp

<div class="grid-2">

    {{-- اطلاعات --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="info-card">
            <div class="card-title"><i class="fas fa-info-circle"></i> اطلاعات مشاوره</div>
            <div class="info-row"><span class="info-label">عنوان</span><span class="info-value">{{ $consultation->title }}</span></div>
            <div class="info-row"><span class="info-label">نوع</span><span class="info-value">{{ $typeLabels[$consultation->type] ?? $consultation->type }}</span></div>
            <div class="info-row"><span class="info-label">وضعیت</span><span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span></div>
            @if($consultation->scheduled_at)
            <div class="info-row">
                <span class="info-label">تاریخ</span>
                <span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->scheduled_at)->format('Y/m/d') }} ساعت {{ $consultation->scheduled_at->format('H:i') }}</span>
            </div>
            @endif
            <div class="info-row"><span class="info-label">مبلغ</span><span class="info-value" style="color:var(--gold-dark);">{{ number_format($consultation->price) }} تومان</span></div>
            <div class="info-row"><span class="info-label">تاریخ ثبت</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($consultation->created_at)->format('Y/m/d') }}</span></div>
            @if($consultation->cancellation_reason)
            <div class="info-row"><span class="info-label">دلیل لغو/رد</span><span class="info-value" style="color:#b91c1c;">{{ $consultation->cancellation_reason }}</span></div>
            @endif
        </div>

        {{-- یادداشت وکیل --}}
        <div class="info-card">
            <div class="card-title"><i class="fas fa-sticky-note"></i> یادداشت وکیل</div>
            @if($consultation->lawyer_notes)
                <div class="note-box"><p>{{ $consultation->lawyer_notes }}</p></div>
            @endif
            <form method="POST" action="{{ route('lawyer.consultations.note', $consultation) }}" style="margin-top:14px;">
                @csrf
                <textarea name="lawyer_notes" rows="4" style="width:100%;padding:12px;border:1.5px solid #e0e0e0;border-radius:8px;font-family:'Vazirmatn',sans-serif;font-size:0.9rem;resize:vertical;outline:none;" placeholder="یادداشت خود را اینجا بنویسید...">{{ $consultation->lawyer_notes }}</textarea>
                <button type="submit" style="margin-top:10px;padding:10px 20px;background:var(--navy);color:#fff;border:none;border-radius:8px;font-family:'Vazirmatn',sans-serif;font-weight:700;cursor:pointer;">
                    <i class="fas fa-save"></i> ذخیره یادداشت
                </button>
            </form>
        </div>
    </div>

    {{-- سایدبار --}}
    <div>
        <div class="client-box">
            <div class="client-avatar">{{ mb_substr($consultation->user->name ?? 'م', 0, 1) }}</div>
            <h4>{{ $consultation->user->name ?? 'موکل' }}</h4>
            <p>{{ $consultation->user->phone ?? '' }}</p>
            @if($consultation->user->national_code)
                <p>کد ملی: {{ $consultation->user->national_code }}</p>
            @endif
        </div>

        <div class="action-box">
            <div class="card-title"><i class="fas fa-bolt"></i> عملیات</div>
            <div class="action-btns">
                @if($consultation->isPending())
                    <form method="POST" action="{{ route('lawyer.consultations.confirm', $consultation) }}">
                        @csrf
                        <button type="submit" class="btn-act btn-confirm-big">
                            <i class="fas fa-check-circle"></i> تأیید مشاوره
                        </button>
                    </form>
                    <button onclick="openModal('rejectModal')" class="btn-act btn-cancel-big">
                        <i class="fas fa-times-circle"></i> رد مشاوره
                    </button>
                @endif

                @if($consultation->isConfirmed())
                    <form method="POST" action="{{ route('lawyer.consultations.complete', $consultation) }}">
                        @csrf
                        <button type="submit" class="btn-act btn-complete-big">
                            <i class="fas fa-flag-checkered"></i> تکمیل مشاوره
                        </button>
                    </form>
                    <button onclick="openModal('cancelModal')" class="btn-act btn-cancel-big">
                        <i class="fas fa-ban"></i> لغو مشاوره
                    </button>
                @endif

                <a href="{{ route('lawyer.consultations.index') }}" class="btn-act btn-back">
                    <i class="fas fa-list"></i> بازگشت به لیست
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal رد --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <h3><i class="fas fa-times-circle" style="color:#b91c1c;margin-left:8px;"></i>رد مشاوره</h3>
        <form method="POST" action="{{ route('lawyer.consultations.reject', $consultation) }}">
            @csrf
            <textarea name="cancellation_reason" required placeholder="دلیل رد مشاوره را بنویسید..."></textarea>
            <div class="modal-btns">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('rejectModal')">انصراف</button>
                <button type="submit" style="padding:10px 18px;background:#b91c1c;color:#fff;border:none;border-radius:8px;cursor:pointer;font-family:'Vazirmatn',sans-serif;font-weight:700;">رد کردن</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal لغو --}}
<div class="modal-overlay" id="cancelModal">
    <div class="modal">
        <h3><i class="fas fa-ban" style="color:#b45309;margin-left:8px;"></i>لغو مشاوره</h3>
        <form method="POST" action="{{ route('lawyer.consultations.cancel', $consultation) }}">
            @csrf
            <textarea name="cancellation_reason" required placeholder="دلیل لغو مشاوره را بنویسید..."></textarea>
            <div class="modal-btns">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('cancelModal')">انصراف</button>
                <button type="submit" style="padding:10px 18px;background:#b45309;color:#fff;border:none;border-radius:8px;cursor:pointer;font-family:'Vazirmatn',sans-serif;font-weight:700;">لغو مشاوره</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if(e.target === m) m.classList.remove('open'); });
});
</script>
@endpush

@endsection