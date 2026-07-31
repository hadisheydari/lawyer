@extends('layouts.lawyer')
@section('title', 'ویرایش مقاله: ' . $article->title)

@push('styles')
<style>
    .content-body { padding: 0 !important; background: #f8fafc !important; margin: 0 !important; }
    .form-wrapper { width: 100% !important; padding: 30px 40px !important; }
    .article-grid { display: grid; grid-template-columns: 1fr 380px; gap: 30px; align-items: start; }
    .card { background: #fff !important; border-radius: 16px !important; box-shadow: 0 4px 25px rgba(0,0,0,0.05) !important; border: 1px solid #eef2f6 !important; margin-bottom: 25px; overflow: hidden; }
    .card-title { padding: 22px 30px; background: #fdfbf7; border-bottom: 2px solid #f9f1d8; color: var(--navy); font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 12px; }
    .card-body { padding: 35px; }

    .lux-input { width: 100% !important; padding: 16px 20px !important; border: 2px solid #e2e8f0 !important; border-radius: 12px !important; background-color: #fff !important; color: var(--navy-dark) !important; font-family: 'Vazirmatn', sans-serif !important; font-size: 1rem !important; font-weight: 600 !important; outline: none !important; transition: all 0.3s ease !important; }
    .lux-input:focus { border-color: var(--gold-main) !important; box-shadow: 0 0 0 5px rgba(212, 175, 55, 0.1) !important; }

    .content-area { min-height: 700px !important; line-height: 2.2 !important; resize: vertical; font-size: 1.15rem !important; }
    .input-label { display: block; font-size: 0.95rem; font-weight: 800; color: #334155; margin-bottom: 12px; }

    .btn-update { width: 100%; background: var(--navy); color: #fff; padding: 20px; border: none; border-radius: 14px; font-weight: 900; font-size: 1.1rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 12px; }
    .btn-update:hover { background: var(--gold-main); transform: translateY(-3px); }

    .current-thumb-box { position: relative; border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; margin-bottom: 15px; }
    .current-thumb-box img { width: 100%; display: block; height: 200px; object-fit: cover; }
    .change-img-btn { margin-top: 10px; display: inline-block; color: var(--gold-dark); font-weight: 800; font-size: 0.85rem; cursor: pointer; }

    @media (max-width: 1100px) { .article-grid { grid-template-columns: 1fr; } .form-wrapper { padding: 20px !important; } }
</style>
@endpush

@section('content')
<div class="form-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; padding-bottom: 25px; border-bottom: 2px solid #e2e8f0;">
        <div>
            <nav style="margin-bottom: 15px;">
                <a href="{{ route('lawyer.articles.index') }}" style="color: var(--gold-dark); text-decoration: none; font-weight: 700;"><i class="fas fa-arrow-right"></i> بازگشت</a>
            </nav>
            <h2 style="font-weight: 900; color: var(--navy); font-size: 2.2rem; margin: 0;">ویرایش مقاله</h2>
        </div>
    </div>

    <form action="{{ route('lawyer.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div class="article-grid">
            <div class="main-content-column">
                <div class="card" style="border-right: 8px solid var(--navy) !important;">
                    <div class="card-body">
                        <div style="margin-bottom: 40px;">
                            <label class="input-label">عنوان اصلی مقاله</label>
                            <input type="text" name="title" class="lux-input" value="{{ old('title', $article->title) }}" required>
                        </div>
                        <div style="margin-bottom: 40px;">
                            <label class="input-label">خلاصه کوتاه</label>
                            <textarea name="excerpt" class="lux-input" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>
                        <div>
                            <label class="input-label">متن بدنه مقاله</label>
                            <textarea name="content" class="lux-input content-area">{{ old('content', $article->content) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-column">
                
                <div class="card" style="border-top: 8px solid var(--gold-main) !important;">
                    <div class="card-title"><i class="fas fa-rocket"></i> وضعیت و تنظیمات</div>
                    <div class="card-body">
                        <div style="margin-bottom: 25px;">
                            <label class="input-label">دسته‌بندی‌ها <small style="color:#94a3b8;">(چند دسته با کاما جدا کنید)</small></label>
                            <input type="text" name="categories" list="categoryList" class="lux-input"
                                value="{{ old('categories', $article->categories->pluck('name')->implode(', ')) }}">
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label class="input-label">خدمت مرتبط</label>
                            <select name="service_id" class="lux-input">
                                <option value="">بدون خدمت...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id', $article->service_id) == $service->id)>{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label class="input-label">وضعیت مقاله</label>
                            <select name="status" class="lux-input">
                                <option value="published" @selected($article->status == 'published')>منتشر شده</option>
                                <option value="draft" @selected($article->status == 'draft')>پیش‌نویس</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-update"><i class="fas fa-save"></i> بروزرسانی نهایی</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title"><i class="fas fa-image"></i> تصویر شاخص</div>
                    <div class="card-body" style="text-align: center;">
                        <div class="current-thumb-box" id="thumbPreviewBox">
                            @if($article->featured_image)
                                <img id="image_preview" src="{{ asset('assets/images/' . $article->featured_image) }}">
                            @else
                                <div style="height: 150px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">بدون تصویر</div>
                            @endif
                        </div>
                        <span class="change-img-btn" onclick="document.getElementById('thumbnail_input').click()"><i class="fas fa-camera"></i> تغییر عکس</span>
                        <input type="file" name="featured_image" id="thumbnail_input" hidden accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>

                <div class="card">
                    <div class="card-title"><i class="fas fa-tags"></i> برچسب‌های سئو</div>
                    <div class="card-body">
                        @php $tagsValue = is_array($article->tags) ? implode(', ', $article->tags) : $article->tags; @endphp
                        <input type="text" name="tags" class="lux-input" value="{{ old('tags', $tagsValue) }}">
                    </div>
                </div>

                <div class="card">
                    <div class="card-title"><i class="fas fa-search"></i> بهینه‌سازی برای گوگل</div>
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <label class="input-label">عنوان متا (Meta Title)</label>
                            <input type="text" name="meta_title" class="lux-input" value="{{ old('meta_title', $article->meta_title) }}">
                        </div>
                        <div>
                            <label class="input-label">توضیحات متا (Meta Description)</label>
                            <textarea name="meta_description" class="lux-input" rows="3">{{ old('meta_description', $article->meta_description) }}</textarea>
                        </div>
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
                const img = document.getElementById('image_preview');
                if (img) img.src = e.target.result;
                else document.getElementById('thumbPreviewBox').innerHTML = `<img id="image_preview" src="${e.target.result}">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush