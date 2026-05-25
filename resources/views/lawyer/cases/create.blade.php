@extends('layouts.lawyer')
@section('title', 'ثبت پرونده جدید')

@push('styles')
    <style>
        /* 1. حذف پدینگ پیش‌فرض داشبورد برای تمام‌عرض شدن */
        .content-body {
            padding: 0 !important;
            background: #f8fafc !important;
        }

        .form-wrapper {
            width: 100% !important;
            max-width: none !important;
            padding: 30px 40px !important;
            padding-bottom: 60px !important;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
        }

        .page-head h2 {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .back-btn {
            padding: 12px 25px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #cbd5e1;
            color: #64748b;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #f1f5f9;
            color: var(--navy);
        }

        .card {
            background: #fff !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid #eef2f6 !important;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-title {
            padding: 22px 30px;
            background: #fdfbf7;
            border-bottom: 2px solid #f9f1d8;
            color: var(--navy);
            font-size: 1.15rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 35px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .full-width {
            grid-column: span 2;
        }

        .input-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 800;
            color: #334155;
            margin-bottom: 12px;
        }

        /* 🔴 استایل لوکس اینپوت‌ها با اعمال !important برای شکست دادن Tailwind 🔴 */
        .lux-input {
            width: 100% !important;
            padding: 16px 20px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 12px !important;
            background-color: #fff !important;
            color: var(--navy-dark) !important;
            font-family: 'Vazirmatn', sans-serif !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            outline: none !important;
            transition: all 0.3s ease !important;
            box-shadow: none !important;
        }

        .lux-input:focus {
            background-color: #fff !important;
            border-color: var(--gold-main) !important;
            box-shadow: 0 0 0 5px rgba(212, 175, 55, 0.1) !important;
        }

        .lux-input.is-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        select.lux-input {
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: left 15px center !important;
            background-size: 16px !important;
        }

        .error-text {
            color: #ef4444;
            font-size: 0.85rem;
            font-weight: 800;
            margin-top: 8px;
            display: block;
        }

        .file-upload-wrapper {
            border: 2px dashed #cbd5e1;
            padding: 40px 20px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            background: #f8fafc;
        }

        .file-upload-wrapper:hover {
            border-color: var(--gold-main);
            background: #fdfbf7;
        }

        .file-upload-wrapper i {
            font-size: 3rem;
            color: var(--gold-main);
            margin-bottom: 15px;
            display: block;
        }

        .btn-submit {
            background: var(--navy);
            color: #fff;
            padding: 20px 45px;
            border: none;
            border-radius: 14px;
            font-weight: 900;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-family: inherit;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
        }

        .btn-submit:hover {
            background: var(--gold-main);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.3);
        }

        @media (max-width: 1024px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: span 1;
            }
        }

        @media (max-width: 1300px) {
            .form-wrapper {
                padding: 20px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-wrapper">
        <div class="page-head">
            <div>
                <nav style="margin-bottom: 15px;">
                    <a href="{{ route('lawyer.cases.index') }}"
                        style="color: var(--gold-dark); text-decoration: none; font-weight: 700; font-size: 0.9rem;">
                        <i class="fas fa-arrow-right"></i> مدیریت پرونده‌ها
                    </a>
                </nav>
                <h2><i class="fas fa-folder-plus" style="color: var(--gold-main); margin-left: 15px;"></i> تشکیل پرونده
                    حقوقی جدید</h2>
            </div>
            <a href="{{ route('lawyer.cases.index') }}" class="back-btn">
                انصراف
            </a>
        </div>

        <form method="POST" action="{{ route('lawyer.cases.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="card" style="border-right: 8px solid var(--navy) !important;">
                <div class="card-title"><i class="fas fa-address-card"></i> مشخصات اصلی پرونده</div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="input-label">انتخاب موکل</label>
                            <select name="user_id" class="lux-input @error('user_id') is-error @enderror" required>
                                <option value="">-- موکل را انتخاب کنید --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('user_id') == $client->id)>{{ $client->name }}
                                        ({{ $client->phone }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label">خدمت حقوقی مرتبط</label>
                            <select name="service_id" class="lux-input">
                                <option value="">-- بدون خدمت مشخص --</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label class="input-label">عنوان پرونده</label>
                            <input type="text" name="title" class="lux-input @error('title') is-error @enderror"
                                placeholder="مثال: دعوای اثبات مالکیت پلاک ثبتی..." value="{{ old('title') }}" required>
                            @error('title')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label class="input-label">شرح و توضیحات پرونده</label>
                            <textarea name="description" class="lux-input" rows="5"
                                placeholder="خلاصه‌ای از موضوع یا خواسته پرونده را بنویسید...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="border-right: 8px solid var(--gold-main) !important;">
                <div class="card-title"><i class="fas fa-coins"></i> مالی و زمان‌بندی</div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="input-label">حق‌الوکاله کل (تومان)</label>
                            <input type="number" name="total_fee" class="lux-input @error('total_fee') is-error @enderror"
                                placeholder="مثلاً: 50000000" min="0" value="{{ old('total_fee') }}" required>
                            @error('total_fee')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label">تاریخ تشکیل پرونده (شمسی)</label>
                            <input type="text" class="lux-input persian-datepicker" data-pd-target="opened_at_hidden"
                                placeholder="انتخاب تاریخ...">
                            <input type="hidden" name="opened_at" id="opened_at_hidden"
                                value="{{ old('opened_at', date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-paperclip"></i> اسناد و مدارک اولیه</div>
                <div class="card-body">
                    <label class="input-label">آپلود اسناد (تصویر یا PDF)</label>
                    <div class="file-upload-wrapper" onclick="document.getElementById('file-input').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span
                            style="font-size: 1.1rem; font-weight: 800; color: #475569; display: block; margin-bottom: 5px;">فایل‌ها
                            را به اینجا بکشید یا کلیک کنید</span>
                        <span style="font-size: 0.85rem; color: #94a3b8;">در صورت نیاز می‌توانید چند فایل را همزمان انتخاب
                            کنید</span>
                        <input type="file" name="documents[]" id="file-input" multiple style="display: none;"
                            onchange="updateFileName(this)">
                    </div>
                    <div id="file-names" style="margin-top: 15px; color: var(--navy); font-weight: 700; font-size: 0.9rem;">
                    </div>
                </div>
            </div>

            <div style="text-align: left;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> ثبت نهایی پرونده
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // نمایش نام فایل‌های انتخاب شده
        function updateFileName(input) {
            let text = "";
            if (input.files.length > 0) {
                text = "فایل‌های انتخاب شده: " + Array.from(input.files).map(f => f.name).join(' ، ');
            }
            document.getElementById('file-names').innerText = text;
        }
    </script>
@endpush
