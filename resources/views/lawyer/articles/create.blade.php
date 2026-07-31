@extends('layouts.lawyer')
@section('title', 'نگارش مقاله جدید')

@push('styles')
<style>
    .article-page { padding: 20px; }

    .page-top {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 25px; flex-wrap: wrap; gap: 12px;
    }
    .page-top h2 { font-weight: 900; color: var(--navy); font-size: 1.6rem; margin: 0; }
    .back-link { color: #64748b; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; font-size: 0.9rem; }
    .back-link:hover { color: var(--gold-main); }

    .article-grid { display: grid; grid-template-columns: 1fr 320px; gap: 25px; align-items: start; }
    .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #eef2f6; margin-bottom: 20px; overflow: hidden; }
    .card-header { padding: 18px 24px; background: #fdfbf7; border-bottom: 2px solid #f9f1d8; display: flex; align-items: center; gap: 10px; }
    .card-header h3 { font-size: 1rem; font-weight: 900; color: var(--navy); margin: 0; }
    .card-body { padding: 24px; }

    .lux-input { width: 100%; padding: 13px 16px; border: 2px solid #e2e8f0; border-radius: 10px; background-color: #f8fafc; color: var(--navy); font-family: 'Vazirmatn', sans-serif; font-size: 0.95rem; font-weight: 600; outline: none; transition: all 0.3s ease; }
    .lux-input:focus { background-color: #fff; border-color: var(--gold-main); box-shadow: 0 0 0 4px rgba(197, 160, 89, 0.1); }

    .form-label { display: block; font-weight: 800; color: #475569; margin-bottom: 10px; font-size: 0.88rem; }
    .form-group { margin-bottom: 22px; }
    .error-msg { color: #ef4444; font-size: 0.8rem; margin-top: 6px; display: block; font-weight: 700; }
    .content-area { min-height: 400px; line-height: 2; resize: vertical; font-size: 1rem; }
    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    .btn-publish { width: 100%; background: var(--navy); color: #fff; padding: 15px; border: none; border-radius: 12px; font-weight: 900; font-size: 1rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .btn-publish:hover { background: var(--gold-main); transform: translateY(-2px); }

    .upload-area { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 25px 15px; text-align: center; cursor: pointer; background: #f8fafc; transition: 0.3s; }
    .upload-area:hover { border-color: var(--gold-main); background: #fdfbf7; }
    .upload-area i { font-size: 2rem; color: var(--gold-main); margin-bottom: 8px; display: block; }

    @media (max-width: 900px) { .article-grid { grid-template-columns: 1fr; } .two-col { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="article-page">

    <div class="page-top">
        <h2><i class="fas fa-edit" style="color:var(--gold-main);margin-left:10px;"></i>ایجاد مقاله جدید</h2>
        <a href="{{ route('lawyer.articles.index') }}" class="back-link"><i class="fas fa-arrow-right"></i> بازگشت به مقالات</a>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('lawyer.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="article-grid">
            
            <div>
                <div class="card" style="border-right:5px solid var(--navy);">
                    <div class="card-header"><i class="fas fa-align-right"></i><h3>محتوای مقاله</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">عنوان مقاله *</label>
                            <input type="text" name="title" class="lux-input" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">خلاصه متن</label>
                            <textarea name="excerpt" class="lux-input" rows="3">{{ old('excerpt') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">متن اصلی مقاله *</label>
                            <textarea name="content" class="lux-input content-area">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><i class="fas fa-tags"></i><h3>دسته‌بندی و برچسب‌ها</h3></div>
                    <div class="card-body">
                        <div class="two-col">
                            <div class="form-group">
                                <label class="form-label">دسته‌بندی‌ها <small style="color:#94a3b8;">(چند دسته با کاما جدا کنید)</small></label>
                                <input type="text" name="categories" list="categoryList" class="lux-input"
                                    placeholder="مثال: حقوق خانواده, طلاق" value="{{ old('categories') }}">
                                <datalist id="categoryList">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label class="form-label">خدمت مرتبط</label>
                                <select name="service_id" class="lux-input">
                                    <option value="">انتخاب خدمت...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">برچسب‌ها (با کاما جدا کنید)</label>
                            <input type="text" name="tags" class="lux-input" value="{{ old('tags') }}">
                        </div>

                        <div class="two-col">
                            <div class="form-group">
                                <label class="form-label">عنوان متا (SEO)</label>
                                <input type="text" name="meta_title" class="lux-input" value="{{ old('meta_title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">توضیح متا (SEO)</label>
                            <textarea name="meta_description" class="lux-input" rows="2">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card" style="border-top:5px solid var(--gold-main);">
                    <div class="card-header"><i class="fas fa-paper-plane"></i><h3>انتشار</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">وضعیت</label>
                            <select name="status" class="lux-input">
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>انتشار عمومی</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-publish"><i class="fas fa-check"></i> ثبت و انتشار</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><i class="fas fa-image"></i><h3>تصویر شاخص</h3></div>
                    <div class="card-body">
                        <div class="upload-area" onclick="document.getElementById('thumb_input').click()" id="uploadBox">
                            <i class="fas fa-camera"></i><span>انتخاب تصویر کاور</span>
                        </div>
                        <img id="imagePreview" src="" style="display:none;width:100%;border-radius:10px;margin-top:10px;">
                        <input type="file" name="featured_image" id="thumb_input" hidden accept="image/*" onchange="previewImage(this)">
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
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadBox').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush