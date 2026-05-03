@extends('layouts.lawyer')
@section('title', 'نوشتن مقاله جدید')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .article-layout { display:grid; grid-template-columns:1fr 320px; gap:25px; align-items:start; }

    .card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:20px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .form-group { margin-bottom:18px; }
    .form-label { display:block; margin-bottom:8px; font-size:0.88rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; outline:none; transition:0.2s; color:var(--navy); }
    .form-input:focus { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .form-input.is-error { border-color:#ef4444; }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }

    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

    .editor-wrapper { border:1.5px solid #e0e0e0; border-radius:10px; overflow:hidden; transition:0.2s; }
    .editor-wrapper:focus-within { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .editor-toolbar { display:flex; gap:4px; padding:10px 12px; background:#f8fafc; border-bottom:1px solid #e0e0e0; flex-wrap:wrap; }
    .tb-btn { padding:5px 10px; border:none; background:#fff; border-radius:6px; font-size:0.8rem; cursor:pointer; color:#64748b; transition:0.2s; font-family:'Vazirmatn',sans-serif; }
    .tb-btn:hover { background:var(--navy); color:#fff; }
    textarea.editor { width:100%; min-height:300px; border:none; padding:16px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; line-height:1.8; resize:vertical; outline:none; color:var(--navy); }

    .seo-preview { background:#f8fafc; border-radius:10px; padding:16px; margin-top:16px; }
    .seo-title { font-size:1rem; color:#1a0dab; font-weight:600; margin-bottom:4px; }
    .seo-url { font-size:0.78rem; color:#006621; margin-bottom:4px; }
    .seo-desc { font-size:0.82rem; color:#545454; }

    .image-drop { border:2px dashed #e0e0e0; border-radius:10px; padding:24px; text-align:center; cursor:pointer; transition:0.3s; position:relative; }
    .image-drop:hover { border-color:var(--gold-main); background:rgba(197,160,89,0.03); }
    .image-drop input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; }
    .image-drop i { font-size:2rem; color:#ccc; display:block; margin-bottom:8px; }
    .image-drop p { font-size:0.82rem; color:#888; margin:0; }
    #img-preview { max-width:100%; border-radius:8px; display:none; margin-top:10px; }

    .tag-input { display:flex; flex-wrap:wrap; gap:6px; padding:10px; border:1.5px solid #e0e0e0; border-radius:10px; min-height:48px; transition:0.2s; cursor:text; }
    .tag-input:focus-within { border-color:var(--gold-main); }
    .tag-chip { background:rgba(197,160,89,0.1); border:1px solid rgba(197,160,89,0.3); color:var(--gold-dark); padding:4px 10px; border-radius:20px; font-size:0.78rem; font-weight:700; display:flex; align-items:center; gap:5px; }
    .tag-chip button { background:none; border:none; cursor:pointer; color:inherit; font-size:0.7rem; padding:0; }
    .tag-input input { border:none; outline:none; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; flex:1; min-width:80px; background:transparent; }

    .word-count { font-size:0.75rem; color:#94a3b8; text-align:left; margin-top:6px; }

    .status-switch { display:flex; gap:10px; }
    .status-opt { flex:1; }
    .status-opt input { display:none; }
    .status-opt label { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px; border-radius:9px; border:1.5px solid #e0e0e0; cursor:pointer; font-size:0.88rem; font-weight:700; color:#888; transition:0.2s; text-align:center; }
    .status-opt input:checked + label.draft-lbl { border-color:#f59e0b; background:#fef3c7; color:#b45309; }
    .status-opt input:checked + label.pub-lbl { border-color:#10b981; background:#d1fae5; color:#065f46; }

    .btn-submit { width:100%; padding:13px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.95rem; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:9px; transition:0.3s; }
    .btn-submit:hover { transform:translateY(-2px); }

    @media(max-width:960px) { .article-layout { grid-template-columns:1fr; } .form-grid { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.articles.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به مقالات
</a>

<form method="POST" action="{{ route('lawyer.articles.store') }}" enctype="multipart/form-data" id="articleForm">
    @csrf

    <div class="article-layout">

        {{-- ستون اصلی --}}
        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-pen-nib"></i> محتوای مقاله</div>

                <div class="form-group">
                    <label class="form-label">عنوان مقاله *</label>
                    <input type="text" name="title" id="titleInput" class="form-input @error('title') is-error @enderror"
                           placeholder="عنوان جذاب و توصیفی..." value="{{ old('title') }}" required>
                    @error('title')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">چکیده (نمایش در لیست مقالات)</label>
                    <textarea name="excerpt" class="form-input" rows="3"
                              placeholder="خلاصه‌ای کوتاه از مقاله...">{{ old('excerpt') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">متن کامل مقاله *</label>
                    <div class="editor-wrapper">
                        <div class="editor-toolbar">
                            <button type="button" class="tb-btn" onclick="wrapText('**','**')"><b>B</b></button>
                            <button type="button" class="tb-btn" onclick="wrapText('*','*')"><i>I</i></button>
                            <button type="button" class="tb-btn" onclick="insertLine('## ')">H2</button>
                            <button type="button" class="tb-btn" onclick="insertLine('### ')">H3</button>
                            <button type="button" class="tb-btn" onclick="insertLine('- ')">لیست</button>
                            <button type="button" class="tb-btn" onclick="insertLine('> ')">نقل‌قول</button>
                            <button type="button" class="tb-btn" onclick="wrapText('`','`')">کد</button>
                        </div>
                        <textarea name="content" id="editor" class="editor @error('content') is-error @enderror"
                                  placeholder="متن مقاله را اینجا بنویسید...">{{ old('content') }}</textarea>
                    </div>
                    <div class="word-count" id="wordCount">۰ کلمه</div>
                    @error('content')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-search"></i> سئو و متا</div>
                <div class="form-group">
                    <label class="form-label">عنوان متا</label>
                    <input type="text" name="meta_title" class="form-input" placeholder="عنوان برای موتورهای جستجو..."
                           value="{{ old('meta_title') }}" maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">توضیحات متا</label>
                    <textarea name="meta_description" class="form-input" rows="3"
                              placeholder="توضیحات کوتاه برای موتورهای جستجو (۱۵۰ کاراکتر)..."
                              maxlength="500">{{ old('meta_description') }}</textarea>
                </div>
                <div class="seo-preview" id="seoPreview">
                    <div class="seo-title" id="seoTitle">عنوان مقاله شما</div>
                    <div class="seo-url">{{ url('/articles/') }}/عنوان-مقاله</div>
                    <div class="seo-desc" id="seoDesc">توضیحات متا شما اینجا نمایش داده می‌شود...</div>
                </div>
            </div>
        </div>

        {{-- سایدبار --}}
        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-cog"></i> تنظیمات</div>

                <div class="form-group">
                    <label class="form-label">وضعیت انتشار</label>
                    <div class="status-switch">
                        <div class="status-opt">
                            <input type="radio" name="status" id="st-draft" value="draft"
                                   {{ old('status','draft')==='draft' ? 'checked' : '' }}>
                            <label for="st-draft" class="draft-lbl"><i class="fas fa-save"></i> پیش‌نویس</label>
                        </div>
                        <div class="status-opt">
                            <input type="radio" name="status" id="st-pub" value="published"
                                   {{ old('status')==='published' ? 'checked' : '' }}>
                            <label for="st-pub" class="pub-lbl"><i class="fas fa-globe"></i> انتشار</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">خدمت مرتبط</label>
                    <select name="service_id" class="form-input">
                        <option value="">بدون خدمت خاص</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id')==$service->id)>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">دسته‌بندی</label>
                    <input type="text" name="category" class="form-input"
                           placeholder="مثال: حقوق خانواده" value="{{ old('category') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">تگ‌ها (با ویرگول جدا کنید)</label>
                    <input type="text" name="tags" id="tagsInput" class="form-input"
                           placeholder="مثال: طلاق, مهریه, حضانت" value="{{ old('tags') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">زمان مطالعه (دقیقه)</label>
                    <input type="number" name="reading_time" class="form-input" min="1" max="60"
                           placeholder="خودکار" value="{{ old('reading_time') }}" id="readingTime">
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-image"></i> تصویر شاخص</div>
                <div class="image-drop" id="imageDrop">
                    <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp"
                           id="imageInput" onchange="previewImage(this)">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>کلیک کنید یا عکس را بکشید اینجا رها کنید</p>
                    <p style="font-size:0.72rem;color:#bbb;margin-top:4px;">JPG، PNG، WebP — حداکثر ۲ مگابایت</p>
                </div>
                <img id="img-preview" src="" alt="پیش‌نمایش">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> ذخیره مقاله
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
const editor = document.getElementById('editor');
const titleInput = document.getElementById('titleInput');

function wrapText(before, after) {
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const sel = editor.value.substring(start, end);
    editor.value = editor.value.substring(0, start) + before + sel + after + editor.value.substring(end);
    editor.focus();
    editor.setSelectionRange(start + before.length, end + before.length);
    updateWordCount();
}

function insertLine(prefix) {
    const start = editor.selectionStart;
    const lineStart = editor.value.lastIndexOf('\n', start - 1) + 1;
    editor.value = editor.value.substring(0, lineStart) + prefix + editor.value.substring(lineStart);
    editor.focus();
    updateWordCount();
}

function updateWordCount() {
    const text = editor.value.replace(/[#*`>-]/g, '').trim();
    const words = text ? text.split(/\s+/).length : 0;
    document.getElementById('wordCount').textContent = words.toLocaleString('fa-IR') + ' کلمه';
    const readingTime = Math.max(1, Math.ceil(words / 200));
    if (!document.getElementById('readingTime').value) {
        document.getElementById('readingTime').placeholder = readingTime + ' دقیقه (تخمین)';
    }
}

editor.addEventListener('input', updateWordCount);
updateWordCount();

titleInput.addEventListener('input', () => {
    document.getElementById('seoTitle').textContent = titleInput.value || 'عنوان مقاله شما';
});

document.querySelector('[name="meta_description"]').addEventListener('input', function() {
    document.getElementById('seoDesc').textContent = this.value || 'توضیحات متا شما اینجا نمایش داده می‌شود...';
});

function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('img-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('imageDrop').querySelector('i').style.display = 'none';
            document.getElementById('imageDrop').querySelectorAll('p').forEach(p => p.style.display = 'none');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush

@endsection