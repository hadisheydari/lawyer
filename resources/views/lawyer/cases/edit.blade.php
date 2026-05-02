@extends('layouts.lawyer')
@section('title', 'ویرایش پرونده')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }
    .form-card { background:#fff; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.06); max-width:800px; }
    .form-title { font-size:1.2rem; font-weight:900; color:var(--navy); margin-bottom:28px; padding-bottom:14px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .form-title i { color:var(--gold-main); }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .form-group { margin-bottom:18px; }
    .form-label { display:block; margin-bottom:8px; font-size:0.88rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; outline:none; transition:0.2s; color:var(--navy); }
    .form-input:focus { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .form-input.is-error { border-color:#ef4444; }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }
    .btn-row { display:flex; gap:12px; align-items:center; margin-top:10px; }
    .btn-submit { padding:13px 28px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.92rem; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:0.3s; }
    .btn-submit:hover { transform:translateY(-2px); }
    .btn-back { padding:13px 22px; background:#f1f5f9; color:var(--navy); border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.92rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:7px; }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.cases.show', $case) }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به پرونده
</a>

<div class="form-card">
    <div class="form-title"><i class="fas fa-edit"></i> ویرایش پرونده # {{ $case->case_number }}</div>

    <form method="POST" action="{{ route('lawyer.cases.update', $case) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">عنوان پرونده</label>
            <input type="text" name="title" class="form-input @error('title') is-error @enderror"
                   value="{{ old('title', $case->title) }}" required>
            @error('title')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">توضیح</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $case->description) }}</textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">خدمت</label>
                <select name="service_id" class="form-input">
                    <option value="">بدون خدمت</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id', $case->service_id) == $service->id)>
                            {{ $service->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">وضعیت</label>
                <select name="current_status" class="form-input @error('current_status') is-error @enderror" required>
                    <option value="active"  @selected(old('current_status', $case->current_status)==='active')>فعال</option>
                    <option value="on_hold" @selected(old('current_status', $case->current_status)==='on_hold')>معلق</option>
                    <option value="closed"  @selected(old('current_status', $case->current_status)==='closed')>بسته</option>
                    <option value="won"     @selected(old('current_status', $case->current_status)==='won')>برنده</option>
                    <option value="lost"    @selected(old('current_status', $case->current_status)==='lost')>بازنده</option>
                </select>
                @error('current_status')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">حق‌الوکاله کل (تومان)</label>
                <input type="number" name="total_fee" class="form-input @error('total_fee') is-error @enderror"
                       value="{{ old('total_fee', $case->total_fee) }}" min="0" required>
                @error('total_fee')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">مبلغ پرداخت‌شده (تومان)</label>
                <input type="number" name="paid_amount" class="form-input @error('paid_amount') is-error @enderror"
                       value="{{ old('paid_amount', $case->paid_amount) }}" min="0" required>
                @error('paid_amount')<span class="error-msg">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="btn-row">
            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> ذخیره تغییرات</button>
            <a href="{{ route('lawyer.cases.show', $case) }}" class="btn-back"><i class="fas fa-times"></i> انصراف</a>
        </div>
    </form>
</div>

@endsection