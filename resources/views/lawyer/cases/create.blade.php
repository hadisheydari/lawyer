{{-- ===== lawyer/cases/create.blade.php ===== --}}
@extends('layouts.lawyer')
@section('title', 'ایجاد پرونده جدید')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }
    .form-card { background:#fff; border-radius:16px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.06); max-width:800px; }
    .form-title { font-size:1.2rem; font-weight:900; color:var(--navy); margin-bottom:28px; padding-bottom:14px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .form-title i { color:var(--gold-main); }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    .form-group { margin-bottom:18px; }
    .form-label { display:block; margin-bottom:8px; font-size:0.88rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; outline:none; transition:0.2s; color:var(--navy); }
    .form-input:focus { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .form-input.is-error { border-color:#ef4444; }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }
    .btn-submit { padding:13px 30px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.95rem; cursor:pointer; display:inline-flex; align-items:center; gap:9px; transition:0.3s; box-shadow:0 8px 20px rgba(15,23,42,0.15); }
    .btn-submit:hover { transform:translateY(-2px); }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.cases.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به پرونده‌ها
</a>

<div class="form-card">
    <div class="form-title"><i class="fas fa-folder-plus"></i> ایجاد پرونده حقوقی جدید</div>

    <form method="POST" action="{{ route('lawyer.cases.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">موکل</label>
                <select name="user_id" class="form-input @error('user_id') is-error @enderror" required>
                    <option value="">انتخاب موکل...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('user_id') == $client->id)>
                            {{ $client->name }} ({{ $client->phone }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">خدمت مرتبط</label>
                <select name="service_id" class="form-input">
                    <option value="">بدون خدمت مشخص</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                            {{ $service->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">عنوان پرونده</label>
            <input type="text" name="title" class="form-input @error('title') is-error @enderror"
                   placeholder="مثال: دعوای اثبات مالکیت ملک..." value="{{ old('title') }}" required>
            @error('title')<span class="error-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">توضیح پرونده</label>
            <textarea name="description" class="form-input" rows="4"
                      placeholder="خلاصه‌ای از موضوع پرونده...">{{ old('description') }}</textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">حق‌الوکاله کل (تومان)</label>
                <input type="number" name="total_fee" class="form-input @error('total_fee') is-error @enderror"
                       placeholder="0" min="0" value="{{ old('total_fee') }}" required>
                @error('total_fee')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">تاریخ افتتاح</label>
                <input type="date" name="opened_at" class="form-input" value="{{ old('opened_at', date('Y-m-d')) }}">
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-folder-plus"></i> ایجاد پرونده
        </button>
    </form>
</div>

@endsection