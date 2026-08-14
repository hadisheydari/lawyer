@extends('layouts.lawyer')
@section('title', 'جزئیات پرونده: ' . $case->title)

@push('styles')
<style>
    /* --- استایل‌های اصلی صفحه --- */
    .case-container { width: 100%; display: flex; flex-direction: column; gap: 25px; padding-bottom: 50px; }
    .case-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 25px; align-items: start; }
    
    .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; overflow: hidden; }
    .card-header { padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .card-header h3 { font-size: 1.1rem; font-weight: 800; color: var(--navy); margin: 0; display: flex; align-items: center; gap: 10px; }
    .card-body { padding: 24px; }

    .info-item { margin-bottom: 15px; }
    .info-label { color: #64748b; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 5px; }
    .info-value { color: var(--navy); font-weight: 800; font-size: 1rem; }

    /* --- تایم‌لاین --- */
    .timeline { position: relative; padding-right: 20px; border-right: 2px solid #e2e8f0; margin-top: 10px; }
    .timeline-item { position: relative; margin-bottom: 25px; }
    .timeline-item::before { content: ""; position: absolute; right: -27px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: var(--gold-main); border: 3px solid #fff; box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2); }
    .timeline-date { font-size: 0.75rem; color: #94a3b8; font-weight: 700; }
    .timeline-title { font-weight: 800; color: var(--navy); font-size: 0.95rem; margin: 3px 0; }
    .timeline-desc { font-size: 0.85rem; color: #64748b; line-height: 1.6; }

    /* --- بج‌ها و دکمه‌ها --- */
    .badge { padding: 6px 14px; border-radius: 25px; font-size: 0.75rem; font-weight: 800; }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-closed { background: #f1f5f9; color: #475569; }
    
    .btn-action { padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-gold { background: var(--gold-main); color: #fff; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2); }
    .btn-gold:hover { background: var(--gold-dark); transform: translateY(-2px); }

    /* --- بخش مالی --- */
    .financial-card { background: var(--navy-dark); color: #fff; }
    .progress-bar { height: 10px; background: rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden; margin-top: 10px; }
    .progress-fill { height: 100%; background: var(--gold-main); box-shadow: 0 0 15px rgba(212,175,55,0.5); }

    /* 🔴 استایل‌های مربوط به مودال (پنجره پاپ‌آپ) 🔴 */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .custom-modal-overlay.active { opacity: 1; visibility: visible; }
    
    .custom-modal {
        background: #fff; width: 90%; max-width: 600px; border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1); transform: translateY(30px);
        transition: all 0.3s ease; max-height: 90vh; overflow-y: auto;
    }
    .custom-modal-overlay.active .custom-modal { transform: translateY(0); }
    
    .modal-head { padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fdfbf7; border-radius: 20px 20px 0 0; }
    .modal-head h3 { margin: 0; font-size: 1.1rem; font-weight: 900; color: var(--navy); }
    .close-modal { background: none; border: none; font-size: 1.2rem; color: #94a3b8; cursor: pointer; transition: 0.2s; }
    .close-modal:hover { color: #ef4444; }
    
    .modal-body { padding: 25px; }
    
    /* استایل اینپوت‌های داخل مودال */
    .lux-input { width: 100% !important; padding: 12px 16px !important; border: 2px solid #e2e8f0 !important; border-radius: 10px !important; background-color: #f8fafc !important; font-family: 'Vazirmatn', sans-serif !important; font-size: 0.9rem !important; outline: none !important; transition: 0.3s !important; }
    .lux-input:focus { background-color: #fff !important; border-color: var(--gold-main) !important; box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1) !important; }
    .input-label { display: block; font-size: 0.85rem; font-weight: 800; color: #475569; margin-bottom: 8px; }

    @media (max-width:600px){
    .inst-row { grid-template-columns: 1fr; }}
    @media (max-width: 1200px) { .case-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 768px) { .case-grid { grid-template-columns: 1fr; } .header-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; } }
</style>
@endpush

@section('content')
<div class="case-container">
    
    <div class="card" style="border-right: 5px solid var(--gold-main);">
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <span class="info-label">پرونده کلاسه: {{ $case->case_number }}</span>
                <h2 style="margin: 5px 0; font-weight: 900; color: var(--navy);">{{ $case->title }}</h2>
                <div style="display: flex; gap: 10px; align-items: center; margin-top: 8px;">
                    <span class="badge {{ $case->current_status == 'active' ? 'badge-active' : 'badge-closed' }}">
                        {{ $case->current_status == 'active' ? 'در جریان' : 'مختومه' }}
                    </span>
                    <span style="color: #94a3b8; font-size: 0.85rem;"><i class="far fa-calendar-alt"></i> تشکیل: {{ \Morilog\Jalali\Jalalian::fromCarbon($case->opened_at)->format('Y/m/d') }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('lawyer.cases.edit', $case) }}" class="btn-action btn-gold">
                    <i class="fas fa-edit"></i> ویرایش پرونده
                </a>
                <a href="{{ route('lawyer.cases.index') }}" class="btn-action" style="background: #f1f5f9; color: #64748b;">
                    <i class="fas fa-arrow-left"></i> لیست پرونده‌ها
                </a>
            </div>
        </div>
    </div>

    <div class="case-grid">
        
        <div class="column">
            <div class="card info-item">
                <div class="card-header"><h3><i class="fas fa-user-tie"></i> مشخصات موکل</h3></div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div><span class="info-label">نام موکل:</span><span class="info-value">{{ $case->user->name ?? 'نامشخص' }}</span></div>
                        <div><span class="info-label">تلفن تماس:</span><span class="info-value">{{ $case->user->phone ?? '---' }}</span></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> روند پرونده</h3>
                    <button type="button" class="btn-action btn-gold" onclick="openModal('statusModal')" style="padding: 6px 12px; font-size: 0.75rem;">
                        <i class="fas fa-plus"></i> ثبت روند
                    </button>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($case->statusLogs as $log)
                        <div class="timeline-item">
                            <span class="timeline-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($log->status_date)->format('Y/m/d') }}</span>
                            <div class="timeline-title">{{ $log->status_title }}</div>
                            <p class="timeline-desc">{{ $log->description }}</p>
                            @if($log->documents->count() > 0)
                                <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px;">
                                    @foreach($log->documents as $doc)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" title="{{ $doc->title }}" style="font-size: 0.7rem; color: #3b82f6; background: #eff6ff; padding: 3px 8px; border-radius: 5px; text-decoration: none;"><i class="fas fa-paperclip"></i> ضمیمه</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @empty
                        <p style="text-align: center; color: #94a3b8; font-size: 0.85rem;">هنوز هیچ روندی ثبت نشده است.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-wallet"></i> اقساط مالی</h3>
                    <button type="button" class="btn-action btn-gold" onclick="openModal('installmentModal')" style="padding: 6px 12px; font-size: 0.75rem;">
                        <i class="fas fa-plus"></i> ثبت اقساط
                    </button>
                </div>
                <div class="card-body">
                    @forelse($case->installments as $inst)
                    <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 12px; border: 1px solid #edf2f7;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-weight: 800; color: var(--navy);">{{ fa_number($inst->amount) }} تومان</span>
                            @if($inst->status == 'paid')
                                <span class="badge badge-active" style="font-size: 0.65rem;">پرداخت شده</span>
                            @else
                                <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.65rem;">در انتظار</span>
                            @endif
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.75rem; color: #94a3b8;">سررسید: {{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('%d %B %Y') }}</span>
                            @if($inst->status != 'paid')
                                <form action="{{ route('lawyer.payments.installment.mark-paid', $inst->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="paid_at" value="{{ now()->format('Y-m-d H:i:s') }}">
                                    <button type="submit" style="background: none; border: 1px solid var(--gold-main); color: var(--gold-main); padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; cursor: pointer; font-weight: bold; transition: 0.3s;" onmouseover="this.style.background='var(--gold-main)'; this.style.color='#fff';" onmouseout="this.style.background='none'; this.style.color='var(--gold-main)';">تأیید وصول</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p style="text-align: center; color: #94a3b8; font-size: 0.85rem;">قسطی ثبت نشده است.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="column" style="display: flex; flex-direction: column; gap: 25px;">
            <div class="card financial-card">
                <div class="card-body">
                    <h3 style="color: var(--gold-main); font-size: 1rem; margin-bottom: 20px;"><i class="fas fa-coins"></i> تراز مالی پرونده</h3>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8;">حق‌الوکاله کل:</span>
                            <span style="font-weight: 800;">{{ fa_number($case->total_fee) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8;">دریافتی تا کنون:</span>
                            <span style="font-weight: 800; color: #10b981;">{{ fa_number($case->paid_amount) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                            <span style="color: #f87171;">مانده طلب:</span>
                            <span style="font-weight: 800; color: #f87171;">{{ fa_number($case->remaining_fee) }}</span>
                        </div>
                    </div>
                    <div style="margin-top: 25px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--gold-main); font-weight: bold;">
                            <span>پیشرفت مالی</span>
                            <span>{{ $case->total_fee > 0 ? round(($case->paid_amount / $case->total_fee) * 100) : 0 }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $case->total_fee > 0 ? ($case->paid_amount / $case->total_fee) * 100 : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="custom-modal-overlay" id="statusModal">
    <div class="custom-modal">
        <div class="modal-head">
            <h3><i class="fas fa-plus-circle" style="color: var(--gold-main);"></i> ثبت روند جدید پرونده</h3>
            <button class="close-modal" onclick="closeModal('statusModal')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('lawyer.cases.status-log', $case->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div style="margin-bottom: 15px;">
                    <label class="input-label">عنوان وضعیت (مثال: ابلاغیه جدید، جلسه دادگاه)</label>
                    <input type="text" name="status_title" class="lux-input" required>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label class="input-label">تاریخ روند (شمسی)</label>
                    <input type="text" class="lux-input persian-datepicker" data-pd-target="status_date_hidden" required>
                    <input type="hidden" name="status_date" id="status_date_hidden">
                </div>

                <div style="margin-bottom: 15px;">
                    <label class="input-label">توضیحات (قابل مشاهده برای موکل)</label>
                    <textarea name="description" class="lux-input" rows="3"></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label class="input-label">یادداشت محرمانه (فقط برای خودتان)</label>
                    <textarea name="private_notes" class="lux-input" rows="2" style="background: #fffbeb !important; border-color: #fde68a !important;"></textarea>
                </div>

                <div style="margin-bottom: 25px;">
                    <label class="input-label">ضمیمه اسناد (اختیاری)</label>
                    <input type="file" name="documents[]" class="lux-input" multiple accept=".pdf,.jpg,.png,.doc,.docx" style="padding: 8px !important;">
                </div>

                <button type="submit" class="btn-action btn-gold" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">ثبت روند در پرونده</button>
            </div>
        </form>
    </div>
</div>

<div class="custom-modal-overlay" id="installmentModal">
    <div class="custom-modal" style="max-width: 800px;">
        <div class="modal-head">
            <h3><i class="fas fa-coins" style="color: var(--gold-main);"></i> زمان‌بندی اقساط جدید</h3>
            <button class="close-modal" onclick="closeModal('installmentModal')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('lawyer.cases.installments', $case->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background: #fee2e2; color: #991b1b; padding: 10px 15px; border-radius: 8px; font-size: 0.8rem; font-weight: bold; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle"></i> توجه: با ثبت اقساط جدید، تمام اقساط قبلی که پرداخت نشده‌اند حذف خواهند شد.
                </div>

                <div id="installments_container">
                    <div class="inst-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 15px; align-items: end; background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <div>
                            <label class="input-label" style="font-size:0.8rem;">مبلغ قسط (تومان)</label>
                            <input type="number" name="installments[0][amount]" class="lux-input" style="padding: 10px !important;" required>
                        </div>
                        <div>
                            <label class="input-label" style="font-size:0.8rem;">تاریخ سررسید</label>
                            <input type="text" class="lux-input persian-datepicker" data-pd-target="inst_date_0" style="padding: 10px !important;" required>
                            <input type="hidden" name="installments[0][due_date]" id="inst_date_0">
                        </div>
                        <div>
                            <label class="input-label" style="font-size:0.8rem;">بابت (اختیاری)</label>
                            <input type="text" name="installments[0][notes]" class="lux-input" style="padding: 10px !important;" placeholder="مثال: پیش‌پرداخت">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 25px; text-align: left;">
                    <button type="button" onclick="addInstRow()" style="background: #e0e7ff; color: #3730a3; border: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; font-size: 0.8rem; cursor: pointer;">
                        + افزودن قسط بعدی
                    </button>
                </div>

                <button type="submit" class="btn-action btn-gold" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem;">ذخیره و ثبت اقساط</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // کنترل باز و بسته شدن مودال‌ها
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // اضافه کردن ردیف جدید برای قسط
    let instIdx = 0;
    function addInstRow() {
        instIdx++;
        const container = document.getElementById('installments_container');
        const html = `
            <div class="inst-row" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 15px; align-items: end; background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div>
                    <label class="input-label" style="font-size:0.8rem;">مبلغ قسط (تومان)</label>
                    <input type="number" name="installments[${instIdx}][amount]" class="lux-input" style="padding: 10px !important;" required>
                </div>
                <div>
                    <label class="input-label" style="font-size:0.8rem;">تاریخ سررسید</label>
                    <input type="text" class="lux-input persian-datepicker" data-pd-target="inst_date_${instIdx}" style="padding: 10px !important;" required>
                    <input type="hidden" name="installments[${instIdx}][due_date]" id="inst_date_${instIdx}">
                </div>
                <div>
                    <label class="input-label" style="font-size:0.8rem;">بابت (اختیاری)</label>
                    <input type="text" name="installments[${instIdx}][notes]" class="lux-input" style="padding: 10px !important;">
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: #fff; border: none; padding: 10px; border-radius: 8px; cursor: pointer;"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        
        // فراخوانی مجدد لودر تاریخ شمسی برای اینپوت‌های جدید ساخته شده
        if(typeof jalaliDatepicker !== 'undefined') {
            jalaliDatepicker.start();
        }
    }
</script>
@endpush