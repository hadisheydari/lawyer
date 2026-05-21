@extends('layouts.lawyer')
@section('title', 'نگارش مقاله جدید')

@push('styles')
    <style>
        /* 1. حذف محدودیت عرض لایوت اصلی فقط برای این صفحه */
        .main-content,
        .container,
        .content-wrapper {
            max-width: none !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* 2. ایجاد کانتینر تمام‌صفحه اختصاصی */
        .full-page-container {
            width: 100% !important;
            padding: 20px 40px !important;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* 3. گرید عریض دسکتاپ (ستون اصلی 75% | سایدبار 25%) */
        .article-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            align-items: start;
        }

        /* 4. استایل کارت‌ها */
        .card {
            background: #fff !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03) !important;
            border: 1px solid #eef2f6 !important;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            padding: 22px 30px;
            background: #fdfbf7;
            border-bottom: 2px solid #f9f1d8;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .card-body {
            padding: 35px;
        }

        /* 5. استایل لوکس اینپوت‌ها با بردرهای قوی */
        .lux-input {
            width: 100% !important;
            padding: 16px 20px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 14px !important;
            background-color: #f8fafc !important;
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

        /* 6. فضای ویرایشگر متن عریض */
        .content-area {
            min-height: 650px !important;
            line-height: 2.2 !important;
            resize: vertical;
            font-size: 1.1rem !important;
        }

        .btn-publish {
            width: 100%;
            background: var(--navy);
            color: #fff;
            padding: 18px;
            border: none;
            border-radius: 15px;
            font-weight: 900;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
        }

        .btn-publish:hover {
            background: var(--gold-main);
            transform: translateY(-3px);
        }

        /* ریسپانسیو مانیتورهای کوچک */
        @media (max-width: 1200px) {
            .article-grid {
                grid-template-columns: 1fr;
            }

            .full-page-container {
                padding: 20px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="full-page-container">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h2 style="font-weight: 900; color: var(--navy); font-size: 2rem; margin: 0;">
                <i class="fas fa-edit" style="color: var(--gold-main); margin-left: 12px;"></i>
                ایجاد مقاله جدید
            </h2>
            <a href="{{ route('lawyer.articles.index') }}" style="color: #64748b; font-weight: 700; text-decoration: none;">
                <i class="fas fa-times-circle"></i> لغو و بازگشت
            </a>
        </div>

        <form action="{{ route('lawyer.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="article-grid">

                <div class="main-column">
                    <div class="card" style="border-right: 8px solid var(--navy) !important;">
                        <div class="card-body">
                            <div style="margin-bottom: 35px;">
                                <label style="display:block; font-weight: 800; color:#475569; margin-bottom:12px;">عنوان
                                    مقاله</label>
                                <input type="text" name="title" class="lux-input"
                                    placeholder="عنوان مقاله حقوقی خود را اینجا بنویسید..." value="{{ old('title') }}"
                                    required>
                            </div>

                            <div style="margin-bottom: 35px;">
                                <label style="display:block; font-weight: 800; color:#475569; margin-bottom:12px;">خلاصه متن
                                    (برای پیش‌نمایش)</label>
                                <textarea name="excerpt" class="lux-input" rows="3" placeholder="مختصری از محتوای مقاله...">{{ old('excerpt') }}</textarea>
                            </div>

                            <div>
                                <label style="display:block; font-weight: 800; color:#475569; margin-bottom:12px;">متن اصلی
                                    مقاله</label>
                                <textarea name="content" class="lux-input content-area" placeholder="نوشتن را شروع کنید...">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sidebar-column">
                    <div class="card" style="border-top: 8px solid var(--gold-main) !important;">
                        <div class="card-header">
                            <h3><i class="fas fa-paper-plane"></i> انتشار</h3>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 25px;">
                                <label
                                    style="display:block; font-weight: 800; color:#475569; margin-bottom:10px;">وضعیت</label>
                                <select name="status" class="lux-input">
                                    <option value="published">انتشار عمومی</option>
                                    <option value="draft">پیش‌نویس</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <label style="display:block; font-weight: 800; color:#475569; margin-bottom:10px;">تاریخ
                                    (شمسی)</label>
                                <input type="text" class="lux-input persian-datepicker"
                                    data-pd-target="publish_date_hidden" placeholder="انتخاب تاریخ...">
                                <input type="hidden" name="published_at" id="publish_date_hidden"
                                    value="{{ date('Y-m-d') }}">
                            </div>

                            <button type="submit" class="btn-publish">
                                <i class="fas fa-check"></i> ثبت و انتشار
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card">
                            <div class="card">
                                <div class="card-header">
                                    <h3><i class="fas fa-folder-open"></i> دسته‌بندی مقاله</h3>
                                </div>
                                <div class="card-body">

                                    <div style="margin-bottom: 25px;">
                                        <label
                                            style="display:block; font-weight: 800; color:#475569; margin-bottom:10px;">سرویس
                                            مرتبط</label>
                                        <select name="service_id" class="lux-input">
                                            <option value="">بدون سرویس (عمومی)</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            style="display:block; font-weight: 800; color:#475569; margin-bottom:10px;">موضوع
                                            (اختیاری)</label>
                                        <input type="text" name="category" class="lux-input"
                                            placeholder="مثلاً: آموزش‌های حقوقی..." value="{{ old('category') }}">
                                    </div>

                                </div>
                            </div>

                            <div class="card-header">
                                <h3><i class="fas fa-image"></i> تصویر شاخص</h3>
                            </div>
                            <div class="card-body">
                                <div style="border: 2px dashed #cbd5e1; border-radius: 15px; padding: 30px 10px; text-align: center; cursor: pointer; background: #f8fafc;"
                                    onclick="document.getElementById('thumb_input').click()">
                                    <i class="fas fa-camera"
                                        style="font-size: 2.5rem; color: var(--gold-main); margin-bottom: 10px; display: block;"></i>
                                    <span style="font-size: 0.85rem; font-weight: 800; color: #64748b;">انتخاب تصویر
                                        کاور</span>
                                    <input type="file" name="thumbnail" id="thumb_input" hidden accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-tags"></i> برچسب‌ها</h3>
                            </div>
                            <div class="card-body">
                                <input type="text" name="tags" class="lux-input" placeholder="با کاما جدا کنید...">
                            </div>
                        </div>
                    </div>

                </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image_preview').src = e.target.result;
                    document.getElementById('image_preview_container').style.display = 'block';
                    document.getElementById('uploadBox').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('thumbnail_input').value = "";
            document.getElementById('image_preview_container').style.display = 'none';
            document.getElementById('uploadBox').style.display = 'block';
        }
    </script>
@endpush
