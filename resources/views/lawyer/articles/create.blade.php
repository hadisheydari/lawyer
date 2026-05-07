@extends('layouts.lawyer')
@section('title', 'نوشتن مقاله جدید')

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .article-layout { display:grid; grid-template-columns:1fr 310px; gap:25px; align-items:start; }

    .card { background:#fff; border-radius:14px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:20px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .form-group { margin-bottom:18px; }
    .form-label { display:block; margin-bottom:8px; font-size:0.88rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; outline:none; transition:0.2s; color:var(--navy); }
    .form-input:focus { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .form-input.is-error { border-color:#ef4444; }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }

    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

    /* ─── Editor ─── */
    .editor-wrapper { border:1.5px solid #e0e0e0; border-radius:10px; overflow:hidden; transition:0.2s; }
    .editor-wrapper:focus-within { border-color:var(--gold-main); box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .editor-toolbar { display:flex; gap:4px; padding:10px 12px; background:#f8fafc; border-bottom:1px solid #e0e0e0; flex-wrap:wrap; }
    .tb-btn { padding:5px 10px; border:none; background:#fff; border-radius:6px; font-size:0.8rem; cursor:pointer; color:#64748b; transition:0.2s; font-family:'Vazirmatn',sans-serif; border:1px solid #e2e8f0; }
    .tb-btn:hover { background:var(--navy); color:#fff; border-color:var(--navy); }
    textarea.editor { width:100%; min-height:300px; border:none; padding:16px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; line-height:1.8; resize:vertical; outline:none; color:var(--navy); }

    .word-count { font-size:0.75rem; color:#94a3b8; text-align:left; margin-top:6px; }

    /* ─── SEO Preview ─── */
    .seo-preview { background:#f8fafc; border-radius:10px; padding:16px; margin-top:16px; border:1px solid #e2e8f0; }
    .seo-title { font-size:1rem; color:#1a0dab; font-weight:600; margin-bottom:4px; }
    .seo-url { font-size:0.78rem; color:#006621; margin-bottom:4px; }
    .seo-desc { font-size:0.82rem; color:#545454; }

    /* ─── Image Drop ─── */
    .image-drop { border:2px dashed #e0e0e0; border-radius:10px; padding:24px; text-align:center; cursor:pointer; transition:0.3s; position:relative; }
    .image-drop:hover { border-color:var(--gold-main); background:rgba(197,160,89,0.03); }
    .image-drop input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .image-drop i { font-size:2rem; color:#ccc; display:block; margin-bottom:8px; }
    .image-drop p { font-size:0.82rem; color:#888; margin:0; }
    #img-preview { max-width:100%; border-radius:8px; display:none; margin-top:10px; }

    /* ─── Status Switch ─── */
    .status-switch { display:flex; gap:10px; }
    .status-opt { flex:1; }
    .status-opt input { display:none; }
    .status-opt label { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px; border-radius:9px; border:1.5px solid #e0e0e0; cursor:pointer; font-size:0.88rem; font-weight:700; color:#888; transition:0.2s; text-align:center; }
    .status-opt input:checked + label.draft-lbl { border-color:#f59e0b; background:#fef3c7; color:#b45309; }
    .status-opt input:checked + label.pub-lbl { border-color:#10b981; background:#d1fae5; color:#065f46; }

    /* ─── Submit Button ─── */
    .btn-submit { width:100%; padding:13px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.95rem; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:9px; transition:0.3s; }
    .btn-submit:hover { transform:translateY(-2px); opacity:0.95; }

    /* ─── Mobile ─── */
    @media(max-width:960px) {
        .article-layout { grid-template-columns:1fr; }
        .form-grid { grid-template-columns:1fr; }
    }
    @media(max-width:480px) {
        .editor-toolbar { gap:3px; }
        .tb-btn { padding:4px 7px; font-size:0.75rem; }
        textarea.editor { min-height:220px; }
        .card { padding:16px; }
    }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.articles.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به مقالات
</a>

<form method="POST" action="{{ route('lawyer.articles.store') }}" enctype="multipart/form-data" id="articleForm">
    @csrf

    <div class="article-layout">

        {{-- ─── ستون اصلی ─── --}}
        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-pen-nib"></i> محتوای مقاله</div>

                <div class="form-group">
                    <label class="form-label">عنوان مقاله *</label>
                    <input type="text" name="title" id="titleInput"
                           class="form-input @error('title') is-error @enderror"
                           placeholder="عنوان جذاب و توصیفی..."
                           value="{{ old('title') }}" required>
                    @error('title')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">چکیده <span style="color:#aaa;font-weight:400;">(نمایش در لیست مقالات)</span></label>
                    <textarea name="excerpt" class="form-input" rows="3"
                              placeholder="خلاصه‌ای کوتاه از مقاله...">{{ old('excerpt') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">متن کامل مقاله *</label>
                    <div class="editor-wrapper">
                        <div class="editor-toolbar" id="editorToolbar">
                            <button type="button" class="tb-btn" data-action="bold"><b>B</b></button>
                            <button type="button" class="tb-btn" data-action="italic"><i>I</i></button>
                            <button type="button" class="tb-btn" data-action="h2">H2</button>
                            <button type="button" class="tb-btn" data-action="h3">H3</button>
                            <button type="button" class="tb-btn" data-action="list">لیست</button>
                            <button type="button" class="tb-btn" data-action="quote">نقل‌قول</button>
                            <button type="button" class="tb-btn" data-action="code">کد</button>
                        </div>
                        <textarea name="content" id="editor"
                                  class="editor @error('content') is-error @enderror"
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
                    <input type="text" name="meta_title" id="metaTitleInput" class="form-input"
                           placeholder="عنوان برای موتورهای جستجو..." value="{{ old('meta_title') }}" maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">توضیحات متا</label>
                    <textarea name="meta_description" id="metaDescInput" class="form-input" rows="3"
                              placeholder="توضیحات کوتاه برای موتورهای جستجو..." maxlength="500">{{ old('meta_description') }}</textarea>
                </div>
                <div class="seo-preview">
                    <div class="seo-title" id="seoTitle">عنوان مقاله شما</div>
                    <div class="seo-url">{{ url('/articles/') }}/عنوان-مقاله</div>
                    <div class="seo-desc" id="seoDesc">توضیحات متا شما اینجا نمایش داده می‌شود...</div>
                </div>
            </div>
        </div>

        {{-- ─── سایدبار ─── --}}
        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-cog"></i> تنظیمات انتشار</div>

                <div class="form-group">
                    <label class="form-label">وضعیت</label>
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
                    <label class="form-label">تگ‌ها <span style="color:#aaa;font-weight:400;">(با ویرگول جدا کنید)</span></label>
                    <input type="text" name="tags" class="form-input"
                           placeholder="مثال: طلاق, مهریه, حضانت" value="{{ old('tags') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">زمان مطالعه (دقیقه)</label>
                    <input type="number" name="reading_time" id="readingTimeInput" class="form-input"
                           min="1" max="60" placeholder="خودکار" value="{{ old('reading_time') }}">
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-image"></i> تصویر شاخص</div>
                <div class="image-drop" id="imageDrop">
                    <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp"
                           id="imageInput">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>کلیک کنید یا تصویر را اینجا رها کنید</p>
                    <p style="font-size:0.72rem;color:#bbb;margin-top:4px;">JPG، PNG، WebP — حداکثر ۲ مگابایت</p>
                </div>
                <img id="img-preview" src="" alt="پیش‌نمایش تصویر">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> ذخیره مقاله
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('editor');
    const titleInput = document.getElementById('titleInput');
    const metaDescInput = document.getElementById('metaDescInput');
    const wordCountEl = document.getElementById('wordCount');
    const readingTimeInput = document.getElementById('readingTimeInput');

    if (!editor) return;

    // ─── Toolbar ───────────────────────────────────────────
    const actions = {
        bold:  { wrap: ['**', '**'] },
        italic:{ wrap: ['*', '*'] },
        code:  { wrap: ['`', '`'] },
        h2:    { line: '## ' },
        h3:    { line: '### ' },
        list:  { line: '- ' },
        quote: { line: '> ' },
    };

    document.querySelectorAll('.tb-btn[data-action]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var action = this.getAttribute('data-action');
            var def = actions[action];
            if (!def) return;

            var start = editor.selectionStart;
            var end   = editor.selectionEnd;
            var val   = editor.value;
            var sel   = val.substring(start, end);

            if (def.wrap) {
                var before = def.wrap[0], after = def.wrap[1];
                editor.value = val.substring(0, start) + before + sel + after + val.substring(end);
                editor.setSelectionRange(start + before.length, end + before.length);
            } else if (def.line) {
                var lineStart = val.lastIndexOf('\n', start - 1) + 1;
                editor.value = val.substring(0, lineStart) + def.line + val.substring(lineStart);
            }

            editor.focus();
            updateWordCount();
        });
    });

    // ─── Word Count ─────────────────────────────────────────
    function updateWordCount() {
        var text = editor.value.replace(/[#*`>~_\[\]()!\-]/g, '').trim();
        var words = text ? text.split(/\s+/).length : 0;
        var persianWords = words.toLocaleString('fa-IR');
        wordCountEl.textContent = persianWords + ' کلمه';

        var readingTime = Math.max(1, Math.ceil(words / 200));
        if (!readingTimeInput.value) {
            readingTimeInput.placeholder = readingTime + ' دقیقه (تخمین)';
        }
    }

    editor.addEventListener('input', updateWordCount);
    updateWordCount();

    // ─── SEO Preview ────────────────────────────────────────
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            var seoTitleEl = document.getElementById('seoTitle');
            if (seoTitleEl) seoTitleEl.textContent = this.value || 'عنوان مقاله شما';
        });
    }

    if (metaDescInput) {
        metaDescInput.addEventListener('input', function() {
            var seoDescEl = document.getElementById('seoDesc');
            if (seoDescEl) seoDescEl.textContent = this.value || 'توضیحات متا شما اینجا نمایش داده می‌شود...';
        });
    }

    // ─── Image Preview ──────────────────────────────────────
    var imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('img-preview');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                var drop = document.getElementById('imageDrop');
                if (drop) {
                    drop.querySelector('i').style.display = 'none';
                    drop.querySelectorAll('p').forEach(function(p) { p.style.display = 'none'; });
                }
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush

@endsection