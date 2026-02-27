@extends('layouts.public')
@section('title', $article->meta_title ?? $article->title . ' | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
<style>
    .article-page { max-width: 1200px; margin: 0 auto; padding: 70px 20px; }

    /* ─── Layout اصلی ────────────────────────────────────────────── */
    .article-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 40px;
        align-items: start;
    }

    /* ─── محتوای مقاله ───────────────────────────────────────────── */
    .article-main {}

    .article-header {
        background: #fff; border-radius: 24px;
        padding: 45px; margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .article-category-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(207,168,110,0.12);
        color: var(--gold-dark); font-size: 0.78rem; font-weight: 700;
        padding: 5px 14px; border-radius: 30px; margin-bottom: 20px;
    }

    .article-title {
        font-size: clamp(1.4rem, 3vw, 2rem);
        font-weight: 900; color: var(--text-heading);
        line-height: 1.5; margin-bottom: 20px;
    }

    .article-meta-bar {
        display: flex; align-items: center; gap: 20px;
        flex-wrap: wrap; padding: 18px 0;
        border-top: 1px solid #f0ece5;
        border-bottom: 1px solid #f0ece5;
        margin-bottom: 30px;
        font-size: 0.82rem; color: var(--text-body);
    }
    .article-meta-bar .meta-item {
        display: flex; align-items: center; gap: 6px; font-weight: 600;
    }
    .article-meta-bar .meta-item i { color: var(--gold-main); }

    /* عکس شاخص */
    .article-featured-img {
        width: 100%; max-height: 450px; object-fit: cover;
        border-radius: 16px; margin-bottom: 30px;
        display: block;
    }

    /* excerpt */
    .article-excerpt-box {
        background: linear-gradient(135deg, #f7f3ed, #fdfbf7);
        border-right: 4px solid var(--gold-main);
        border-radius: 12px; padding: 20px 25px;
        margin-bottom: 30px;
        font-size: 1rem; color: var(--text-heading);
        line-height: 1.9; font-weight: 600;
    }

    /* محتوای اصلی */
    .article-body {
        font-size: 1rem; color: var(--text-body);
        line-height: 2.1; text-align: justify;
    }
    .article-body h2, .article-body h3 {
        color: var(--text-heading); font-weight: 900;
        margin: 35px 0 15px; line-height: 1.4;
    }
    .article-body h2 { font-size: 1.3rem; border-right: 3px solid var(--gold-main); padding-right: 15px; }
    .article-body h3 { font-size: 1.1rem; }
    .article-body p { margin-bottom: 18px; }
    .article-body ul, .article-body ol {
        padding-right: 25px; margin-bottom: 18px;
    }
    .article-body li { margin-bottom: 8px; }
    .article-body strong { color: var(--text-heading); }
    .article-body a { color: var(--gold-main); text-decoration: underline; }
    .article-body blockquote {
        background: #f7f3ed; border-right: 4px solid var(--gold-main);
        padding: 20px 25px; border-radius: 8px; margin: 25px 0;
        font-style: italic; color: var(--text-heading);
    }

    /* تگ‌ها */
    .article-tags {
        display: flex; flex-wrap: wrap; gap: 8px; margin-top: 35px;
        padding-top: 25px; border-top: 1px solid #f0ece5;
    }
    .tag-item {
        background: #f0f4f8; color: var(--navy);
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
    }

    /* ─── Sidebar ─────────────────────────────────────────────────── */
    .article-sidebar { display: flex; flex-direction: column; gap: 25px; position: sticky; top: 90px; }

    /* کارت وکیل نویسنده */
    .lawyer-card-s {
        background: #fff; border-radius: 20px; overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        border-top: 4px solid var(--gold-main);
    }
    .lawyer-card-s-header {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        padding: 25px; text-align: center;
    }
    .lawyer-avatar-s {
        width: 70px; height: 70px; border-radius: 50%;
        background: rgba(207,168,110,0.2);
        border: 3px solid rgba(207,168,110,0.5);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.6rem; font-weight: 900; color: var(--gold-main);
        overflow: hidden;
    }
    .lawyer-avatar-s img { width: 100%; height: 100%; object-fit: cover; }
    .lawyer-card-s-header h3 { color: #fff; font-size: 1rem; font-weight: 800; margin-bottom: 4px; }
    .lawyer-card-s-header p { color: rgba(255,255,255,0.6); font-size: 0.78rem; }
    .lawyer-card-s-body { padding: 20px; }
    .btn-consult-s {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; padding: 12px; border-radius: 10px;
        font-weight: 700; font-size: 0.88rem;
        box-shadow: 0 5px 15px rgba(207,168,110,0.3);
        transition: 0.3s; text-decoration: none; width: 100%;
    }
    .btn-consult-s:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(207,168,110,0.5); color: #fff; }

    /* مقالات مرتبط */
    .related-box {
        background: #fff; border-radius: 20px; padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .related-box h3 {
        font-size: 0.95rem; font-weight: 900; color: var(--text-heading);
        margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    }
    .related-box h3 i { color: var(--gold-main); }
    .related-item {
        display: flex; gap: 12px; padding: 12px 0;
        border-bottom: 1px solid #f0ece5;
        text-decoration: none; color: inherit; transition: 0.3s;
    }
    .related-item:last-child { border-bottom: none; padding-bottom: 0; }
    .related-item:hover { color: var(--gold-main); }
    .related-img {
        width: 65px; height: 65px; border-radius: 10px;
        object-fit: cover; flex-shrink: 0;
        background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
    }
    .related-img-placeholder {
        width: 65px; height: 65px; border-radius: 10px;
        background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .related-img-placeholder i { color: rgba(207,168,110,0.4); font-size: 1.2rem; }
    .related-text h4 { font-size: 0.82rem; font-weight: 700; line-height: 1.5; margin-bottom: 5px; }
    .related-text span { font-size: 0.72rem; color: var(--text-body); }

    /* اشتراک‌گذاری */
    .share-box {
        background: #fff; border-radius: 20px; padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }
    .share-box h3 { font-size: 0.95rem; font-weight: 900; color: var(--text-heading); margin-bottom: 15px; }
    .share-btns { display: flex; gap: 10px; }
    .share-btn {
        flex: 1; padding: 10px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; transition: 0.3s; cursor: pointer; border: none;
        font-family: inherit;
    }
    .share-btn.telegram { background: rgba(0,136,204,0.1); color: #0088cc; }
    .share-btn.whatsapp { background: rgba(37,211,102,0.1); color: #25d366; }
    .share-btn.copy    { background: rgba(207,168,110,0.1); color: var(--gold-dark); }
    .share-btn:hover { opacity: 0.8; transform: translateY(-2px); }

    @media (max-width: 1000px) {
        .article-layout { grid-template-columns: 1fr; }
        .article-sidebar { position: static; }
        .article-header { padding: 30px 25px; }
    }
</style>
@endpush

@section('content')

{{-- Page Banner --}}
<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-newspaper" style="color:var(--gold-main);margin-left:12px;"></i>مقاله حقوقی</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <a href="{{ route('articles.index') }}">مقالات</a>
            <i class="fas fa-chevron-left"></i>
            @if($article->category)
                <a href="{{ route('articles.index', ['cat' => $article->category]) }}">{{ $article->category }}</a>
                <i class="fas fa-chevron-left"></i>
            @endif
            <span>{{ Str::limit($article->title, 40) }}</span>
        </div>
    </div>
</div>

<div class="article-page">
    <div class="article-layout">

        {{-- ─── محتوای اصلی ──────────────────────────────────── --}}
        <main class="article-main">

            <div class="article-header">

                {{-- دسته‌بندی --}}
                @if($article->category)
                    <div class="article-category-badge">
                        <i class="fas fa-folder"></i>
                        <a href="{{ route('articles.index', ['cat' => $article->category]) }}"
                           style="color:inherit;text-decoration:none;">
                            {{ $article->category }}
                        </a>
                    </div>
                @endif

                {{-- عنوان --}}
                <h1 class="article-title">{{ $article->title }}</h1>

                {{-- متا بار --}}
                <div class="article-meta-bar">
                    @if($article->published_at)
                        <span class="meta-item">
                            <i class="far fa-calendar-alt"></i>
                            {{ \Morilog\Jalali\Jalalian::fromCarbon($article->published_at)->format('Y/m/d') }}
                        </span>
                    @endif
                    @if($article->lawyer)
                        <span class="meta-item">
                            <i class="fas fa-user-tie"></i>
                            {{ $article->lawyer->name }}
                        </span>
                    @endif
                    @if($article->reading_time)
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            {{ $article->reading_time }} دقیقه مطالعه
                        </span>
                    @endif
                    @if($article->view_count)
                        <span class="meta-item">
                            <i class="fas fa-eye"></i>
                            {{ number_format($article->view_count) }} بازدید
                        </span>
                    @endif
                </div>

                {{-- عکس شاخص --}}
                @if($article->featured_image)
                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                         alt="{{ $article->title }}"
                         class="article-featured-img">
                @endif

                {{-- خلاصه --}}
                @if($article->excerpt)
                    <div class="article-excerpt-box">
                        {{ $article->excerpt }}
                    </div>
                @endif

                {{-- محتوای اصلی --}}
                <div class="article-body">
                    {!! $article->content !!}
                </div>

                {{-- تگ‌ها --}}
                @if($article->tags && count($article->tags) > 0)
                    <div class="article-tags">
                        <i class="fas fa-tags" style="color:var(--gold-main);margin-left:5px;"></i>
                        @foreach($article->tags as $tag)
                            <span class="tag-item">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

            </div>

        </main>

        {{-- ─── Sidebar ───────────────────────────────────────── --}}
        <aside class="article-sidebar">

            {{-- کارت وکیل نویسنده --}}
            @if($article->lawyer)
                <div class="lawyer-card-s">
                    <div class="lawyer-card-s-header">
                        <div class="lawyer-avatar-s">
                            @if($article->lawyer->image)
                                <img src="{{ asset('storage/' . $article->lawyer->image) }}"
                                     alt="{{ $article->lawyer->name }}">
                            @else
                                {{ mb_substr($article->lawyer->name, 0, 1) }}
                            @endif
                        </div>
                        <h3>{{ $article->lawyer->name }}</h3>
                        <p>نویسنده این مقاله</p>
                    </div>
                    <div class="lawyer-card-s-body">
                        @if($article->lawyer->bio)
                            <p style="font-size:0.82rem;color:var(--text-body);line-height:1.8;margin-bottom:15px;">
                                {{ Str::limit($article->lawyer->bio, 120) }}
                            </p>
                        @endif
                        <a href="{{ route('reserve.index', ['lawyer' => $article->lawyer->slug]) }}"
                           class="btn-consult-s">
                            <i class="fas fa-calendar-check"></i>
                            رزرو مشاوره
                        </a>
                    </div>
                </div>
            @endif

            {{-- مقالات مرتبط --}}
            @if($related->isNotEmpty())
                <div class="related-box">
                    <h3><i class="fas fa-layer-group"></i> مقالات مرتبط</h3>
                    @foreach($related as $rel)
                        <a href="{{ route('articles.show', $rel->slug) }}" class="related-item">
                            @if($rel->featured_image)
                                <img src="{{ asset('storage/' . $rel->featured_image) }}"
                                     alt="{{ $rel->title }}" class="related-img">
                            @else
                                <div class="related-img-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                            <div class="related-text">
                                <h4>{{ Str::limit($rel->title, 55) }}</h4>
                                @if($rel->published_at)
                                    <span>{{ \Morilog\Jalali\Jalalian::fromCarbon($rel->published_at)->format('Y/m/d') }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- اشتراک‌گذاری --}}
            <div class="share-box">
                <h3>اشتراک‌گذاری مقاله</h3>
                <div class="share-btns">
                    <button class="share-btn telegram" onclick="shareArticle('telegram')" title="تلگرام">
                        <i class="fab fa-telegram"></i>
                    </button>
                    <button class="share-btn whatsapp" onclick="shareArticle('whatsapp')" title="واتساپ">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button class="share-btn copy" onclick="copyLink()" title="کپی لینک">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>

            {{-- لینک تماس --}}
            <div style="background:linear-gradient(135deg,var(--navy),#1e3a5f);border-radius:20px;padding:25px;text-align:center;">
                <i class="fas fa-phone-alt" style="font-size:1.8rem;color:var(--gold-main);margin-bottom:12px;display:block;"></i>
                <h3 style="color:#fff;font-size:0.95rem;font-weight:800;margin-bottom:8px;">نیاز به مشاوره دارید؟</h3>
                <p style="color:rgba(255,255,255,0.6);font-size:0.8rem;margin-bottom:18px;">وکلای ما آماده پاسخگویی هستند</p>
                <a href="{{ route('contact') }}" style="display:block;background:var(--gold-main);color:#fff;padding:11px;border-radius:10px;font-weight:700;font-size:0.88rem;text-decoration:none;">
                    تماس با دفتر
                </a>
            </div>

        </aside>

    </div>
</div>

@push('scripts')
<script>
function shareArticle(platform) {
    const url  = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ addslashes($article->title) }}');
    let link;
    if (platform === 'telegram')  link = `https://t.me/share/url?url=${url}&text=${text}`;
    if (platform === 'whatsapp')  link = `https://wa.me/?text=${text}%20${url}`;
    window.open(link, '_blank');
}
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.querySelector('.share-btn.copy i');
        btn.className = 'fas fa-check';
        setTimeout(() => { btn.className = 'fas fa-link'; }, 2000);
    });
}
</script>
@endpush

@endsection