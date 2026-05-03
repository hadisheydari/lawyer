@extends('layouts.lawyer')
@section('title', 'ویرایش مقاله')

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
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }
    .editor-wrapper { border:1.5px solid #e0e0e0; border-radius:10px; overflow:hidden; }
    .editor-wrapper:focus-within { border-color:var(--gold-main); }
    .editor-toolbar { display:flex; gap:4px; padding:10px 12px; background:#f8fafc; border-bottom:1px solid #e0e0e0; flex-wrap:wrap; }
    .tb-btn { padding:5px 10px; border:none; background:#fff; border-radius:6px; font-size:0.8rem; cursor:pointer; color:#64748b; transition:0.2s; }
    .tb-btn:hover { background:var(--navy); color:#fff; }
    textarea.editor { width:100%; min-height:350px; border:none; padding:16px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; line-height:1.8; resize:vertical; outline:none; color:var(--navy); }
    .status-switch { display:flex; gap:10px; }
    .status-opt { flex:1; }
    .status-opt input { display:none; }
    .status-opt label { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px; border-radius:9px; border:1.5px solid #e0e0e0; cursor:pointer; font-size:0.85rem; font-weight:700; color:#888; transition:0.2s; }
    .status-opt input:checked + label.draft-lbl { border-color:#f59e0b; background:#fef3c7; color:#b45309; }
    .status-opt input:checked + label.pub-lbl { border-color:#10b981; background:#d1fae5; color:#065f46; }
    .status-opt input:checked + label.arch-lbl { border-color:#64748b; background:#f1f5f9; color:#475569; }
    .current-image { border-radius:8px; width:100%; margin-bottom:12px; max-height:160px; object-fit:cover; }
    .btn-row { display:flex; gap:10px; }
    .btn-submit { flex:1; padding:13px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.92rem; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:9px; transition:0.3s; }
    .btn-submit:hover { transform:translateY(-2px); }
    .btn-cancel { padding:13px 20px; background:#f1f5f9; color:var(--navy); border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.92rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:7px; }
    @media(max-width:960px) { .article-layout { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.articles.show', $article) }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به مقاله
</a>

<form method="POST" action="{{ route('lawyer.articles.update', $article) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="article-layout">
        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-pen-nib"></i> ویرایش محتوا</div>

                <div class="form-group">
                    <label class="form-label">عنوان مقاله *</label>
                    <input type="text" name="title" class="form-input @error('title') is-error @enderror"
                           value="{{ old('title', $article->title) }}" required>
                    @error('title')<span class="error-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">چکیده</label>
                    <textarea name="excerpt" class="form-input" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
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
                        </div>
                        <textarea name="content" id="editor" class="editor @error('content') is-error @enderror">{{ old('content', $article->content) }}</textarea>
                    </div>
                    @error('content')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-search"></i> سئو</div>
                <div class="form-group">
                    <label class="form-label">عنوان متا</label>
                    <input type="text" name="meta_title" class="form-input"
                           value="{{ old('meta_title', $article->meta_title) }}" maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">توضیحات متا</label>
                    <textarea name="meta_description" class="form-input" rows="3"
                              maxlength="500">{{ old('meta_description', $article->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-title"><i class="fas fa-cog"></i> تنظیمات</div>

                <div class="form-group">
                    <label class="form-label">وضعیت</label>
                    <div class="status-switch">
                        <div class="status-opt">
                            <input type="radio" name="status" id="st-draft" value="draft"
                                   {{ old('status',$article->status)==='draft' ? 'checked' : '' }}>
                            <label for="st-draft" class="draft-lbl"><i class="fas fa-save"></i> پیش‌نویس</label>
                        </div>
                        <div class="status-opt">
                            <input type="radio" name="status" id="st-pub" value="published"
                                   {{ old('status',$article->status)==='published' ? 'checked' : '' }}>
                            <label for="st-pub" class="pub-lbl"><i class="fas fa-globe"></i> انتشار</label>
                        </div>
                        <div class="status-opt">
                            <input type="radio" name="status" id="st-arch" value="archived"
                                   {{ old('status',$article->status)==='archived' ? 'checked' : '' }}>
                            <label for="st-arch" class="arch-lbl"><i class="fas fa-archive"></i> آرشیو</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">خدمت مرتبط</label>
                    <select name="service_id" class="form-input">
                        <option value="">بدون خدمت</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id',$article->service_id)==$service->id)>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">دسته‌بندی</label>
                    <input type="text" name="category" class="form-input"
                           value="{{ old('category', $article->category) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">تگ‌ها (با ویرگول جدا کنید)</label>
                    <input type="text" name="tags" class="form-input"
                           value="{{ old('tags', $article->tags ? implode(', ', $article->tags) : '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">زمان مطالعه (دقیقه)</label>
                    <input type="number" name="reading_time" class="form-input" min="1"
                           value="{{ old('reading_time', $article->reading_time) }}">
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-image"></i> تصویر شاخص</div>
                @if($article->featured_image)
                    <img src="{{ asset('assets/images/'.$article->featured_image) }}"
                         alt="" class="current-image">
                    <p style="font-size:0.75rem;color:#888;margin-bottom:10px;">برای تغییر، عکس جدید انتخاب کنید</p>
                @endif
                <input type="file" name="featured_image" class="form-input"
                       accept="image/jpeg,image/png,image/webp" style="padding:8px;">
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> ذخیره تغییرات
                </button>
                <a href="{{ route('lawyer.articles.show', $article) }}" class="btn-cancel">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
const editor = document.getElementById('editor');
function wrapText(b, a) {
    const s = editor.selectionStart, e = editor.selectionEnd;
    const sel = editor.value.substring(s, e);
    editor.value = editor.value.substring(0,s) + b + sel + a + editor.value.substring(e);
    editor.focus();
}
function insertLine(prefix) {
    const s = editor.selectionStart;
    const ls = editor.value.lastIndexOf('\n', s-1)+1;
    editor.value = editor.value.substring(0,ls) + prefix + editor.value.substring(ls);
    editor.focus();
}
</script>
@endpush

@endsection