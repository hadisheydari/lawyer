@extends('layouts.lawyer')
@section('title', 'ویرایش پرونده')

@push('styles')
<style>
    /* همان کدهای CSS فرم Create را اینجا کپی کن */
    .form-wrapper { max-width: 900px; margin: 0 auto; padding-bottom: 40px; }
    .page-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-head h2 { font-size: 1.4rem; font-weight: 900; color: var(--navy); margin: 0; }
    .back-btn { color: #64748b; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: 0.2s; }
    .back-btn:hover { color: var(--gold-main); }
    .form-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 25px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 25px; border: 1px solid #f1f5f9; }
    .card-title { padding: 22px 25px; background: #fdfbf7; border-bottom: 2px solid #f9f1d8; color: var(--navy); font-size: 1.05rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .card-body { padding: 30px 25px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .full-width { grid-column: span 2; }
    .form-group { margin-bottom: 0; }
    .input-label { display: block; font-size: 0.85rem; font-weight: 800; color: #475569; margin-bottom: 10px; }
    .lux-input { width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc; color: var(--navy-dark); font-family: 'Vazirmatn', sans-serif; font-size: 0.95rem; font-weight: 600; outline: none; transition: all 0.3s ease; }
    .lux-input:focus { background: #fff; border-color: var(--gold-main); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15); }
    .lux-input::placeholder { color: #cbd5e1; font-weight: 500; }
    .lux-input.is-error { border-color: #ef4444; background: #fef2f2; }
    select.lux-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: left 15px center; background-size: 16px; }
    .error-text { color: #ef4444; font-size: 0.75rem; font-weight: 700; margin-top: 6px; display: block; }
    .btn-submit { background: var(--navy); color: #fff; padding: 16px 35px; border: none; border-radius: 12px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 10px; font-family: inherit; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15); }
    .btn-submit:hover { background: var(--gold-main); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3); }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
</style>
@endpush

@section('content')
<div class="form-wrapper">
    <div class="page-head">
        <h2><i class="fas fa-edit" style="color: var(--gold-main); margin-left: 8px;"></i> ویرایش پرونده: {{ $case->case_number }}</h2>
        <a href="{{ route('lawyer.cases.show', $case) }}" class="back-btn"><i class="fas fa-arrow-right"></i> انصراف</a>
    </div>

    <form method="POST" action="{{ route('lawyer.cases.update', $case) }}">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="card-title"><i class="fas fa-sliders-h"></i> مشخصات و وضعیت پرونده</div>
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="input-label">عنوان پرونده</label>
                    <input type="text" name="title" class="lux-input @error('title') is-error @enderror" value="{{ old('title', $case->title) }}" required>
                    @error('title')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="input-label">وضعیت فعلی</label>
                        <select name="current_status" class="lux-input @error('current_status') is-error @enderror" required>
                            <option value="active" @selected(old('current_status', $case->current_status) === 'active')>در جریان (فعال)</option>
                            <option value="on_hold" @selected(old('current_status', $case->current_status) === 'on_hold')>توقف موقت</option>
                            <option value="court" @selected(old('current_status', $case->current_status) === 'court')>وقت دادگاه</option>
                            <option value="closed" @selected(old('current_status', $case->current_status) === 'closed')>مختومه (عادی)</option>
                            <option value="won" @selected(old('current_status', $case->current_status) === 'won')>مختومه (پیروزی)</option>
                            <option value="lost" @selected(old('current_status', $case->current_status) === 'lost')>مختومه (شکست)</option>
                        </select>
                        @error('current_status')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">خدمت حقوقی</label>
                        <select name="service_id" class="lux-input">
                            <option value="">-- بدون خدمت مشخص --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id', $case->service_id) == $service->id)>{{ $service->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label class="input-label">توضیحات و یادداشت</label>
                        <textarea name="description" class="lux-input" rows="4">{{ old('description', $case->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="card-title"><i class="fas fa-wallet"></i> تراز مالی پرونده</div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="input-label">کل حق‌الوکاله (تومان)</label>
                        <input type="number" name="total_fee" class="lux-input @error('total_fee') is-error @enderror" value="{{ old('total_fee', $case->total_fee) }}" min="0" required>
                        @error('total_fee')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">مبلغ پرداخت شده تا الان (تومان)</label>
                        <input type="number" name="paid_amount" class="lux-input @error('paid_amount') is-error @enderror" value="{{ old('paid_amount', $case->paid_amount) }}" min="0" required>
                        @error('paid_amount')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-sync-alt"></i> ذخیره تغییرات
            </button>
        </div>
    </form>
</div>
@endsection