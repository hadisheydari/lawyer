@extends('layouts.public')
@section('title', $article->meta_title ?? $article->title . ' | دفتر وکالت ابدالی و جوشقانی')

@push('styles')
<style>
    .article-page { max-width: 1200px; margin: 0 auto; padding: 70px 20px; }

    .article-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 40px;
        align-items: start;
    }

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

    .article-featured-img {
        width: 100%; max-height: 450px; object-fit: cover;
        border-radius: 16px; margin-bottom: 30px;
        display: block;
    }

    .article-excerpt-box {
        background: linear-gradient(135deg, #f7f3ed, #fdfbf7);
        border-right: 4px solid var(--gold-main);
        border-radius: 12px; padding: 20px 25px;
        margin-bottom: 30px;
        font-size: 1rem; color: var(--text-heading);
        line-height: 1.9; font-weight: 600;
    }

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
    .article-body ul, .article-body ol { padding-right: 25px; margin-bottom: 18px; }
    .article-body li { margin-bottom: 8px; }
    .article-body strong { color: var(--text-heading); }
    .article-body a { color: var(--gold-main); text-decoration: underline; }
    .article-body blockquote {
        background: #f7f3ed; border-right: 4px solid var(--gold-main);
        padding: 20px 25px; border-radius: 8px; margin: 25px 0;
        font-style: italic; color: var(--text-heading);
    }

    .article-tags {
        display: flex; flex-wrap: wrap; gap: 8px; margin-top: 35px;
        padding-top: 25px; border-top: 1px solid #f0ece5;
    }
    .tag-item {
        background: #f0f4f8; color: var(--navy);
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
    }

    /* ─── ری‌اکشن‌ها ──────────────────────────────────────────────── */
    .reactions-section {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        padding: 22px 28px;
        margin: 30px 0;
        background: #fafafa;
        border: 1px solid #ede8e0;
        border-radius: 16px;
    }

    .reactions-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-body);
        white-space: nowrap;
    }

    .reactions-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .reaction-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 30px;
        border: 1.5px solid #e0d8ce;
        background: #fff;
        color: var(--text-body);
        font-size: 0.82rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .reaction-btn i { font-size: 0.9rem; color: #bbb; transition: color 0.2s; }
    .reaction-btn b { font-size: 0.78rem; color: #aaa; font-weight: 700; }
    .reaction-btn:hover { border-color: var(--gold-main); color: var(--gold-dark); background: rgba(207,168,110,0.06); }
    .reaction-btn:hover i { color: var(--gold-main); }
    .reaction-btn.active { background: var(--gold-main); border-color: var(--gold-main); color: #fff; }
    .reaction-btn.active i, .reaction-btn.active b { color: #fff; }

    @media (max-width: 768px) {
        .reactions-section { flex-direction: column; align-items: flex-start; padding: 18px 20px; }
    }

    /* ─── کامنت‌ها ─────────────────────────────────────────────────── */
    .comments-section {
        margin: 50px 0;
        padding: 35px;
        background: #fff;
        border: 1px solid #ede8e0;
        border-radius: 16px;
    }

    .comments-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comments-title i { color: var(--gold-main); font-size: 1.1rem; }

    .comment-form {
        margin-bottom: 35px;
        padding: 25px;
        background: #fafafa;
        border-radius: 12px;
        border: 1px solid #ede8e0;
    }

    .reply-indicator {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        margin-bottom: 15px;
        background: #fff3e0;
        border-right: 3px solid var(--gold-main);
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .reply-indicator strong { color: var(--gold-dark); }

    .reply-indicator button {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }

    .reply-indicator button:hover { color: #d32f2f; }

    .comment-form textarea {
        width: 100%;
        padding: 15px;
        border: 1.5px solid #e0d8ce;
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.9rem;
        resize: vertical;
        transition: border-color 0.2s;
    }

    .comment-form textarea:focus { outline: none; border-color: var(--gold-main); }

    .submit-comment-btn {
        margin-top: 15px;
        padding: 12px 28px;
        background: var(--gold-main);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .submit-comment-btn:hover { background: var(--gold-dark); transform: translateY(-1px); }

    .login-prompt {
        padding: 25px;
        text-align: center;
        background: #f5f5f5;
        border-radius: 12px;
        color: var(--text-body);
        font-size: 0.95rem;
    }

    .login-prompt i { display: block; font-size: 2rem; color: #ccc; margin-bottom: 12px; }
    .login-prompt a { color: var(--gold-main); font-weight: 700; text-decoration: none; }
    .login-prompt a:hover { text-decoration: underline; }

    .comments-list { margin-top: 30px; }

    .comment-item {
        margin-bottom: 20px;
        padding: 20px;
        background: #fafafa;
        border: 1px solid #ede8e0;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .comment-item.hidden-comment { display: none; }

    .comment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .comment-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comment-author i { color: var(--gold-main); font-size: 0.9rem; }
    .comment-author strong { font-size: 0.9rem; font-weight: 700; color: var(--text-heading); }
    .comment-date { font-size: 0.75rem; color: #999; }

    .comment-body {
        font-size: 0.9rem;
        line-height: 1.8;
        color: var(--text-body);
        margin-bottom: 12px;
    }

    .comment-actions { display: flex; gap: 15px; }

    .comment-reply-btn {
        background: none;
        border: none;
        color: var(--gold-main);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.2s;
    }

    .comment-reply-btn:hover { color: var(--gold-dark); }

    .comment-replies {
        margin-top: 15px;
        margin-right: 30px;
        padding-right: 20px;
        border-right: 2px solid #e0d8ce;
    }

    .comment-replies .comment-item { background: #fff; }

    .show-more-comments {
        display: block;
        width: 100%;
        margin-top: 20px;
        padding: 15px;
        background: #fff;
        border: 2px dashed #e0d8ce;
        border-radius: 10px;
        color: var(--gold-main);
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .show-more-comments:hover { background: #fafafa; border-color: var(--gold-main); }
    .show-more-comments i { margin-left: 8px; transition: transform 0.2s; }
    .show-more-comments:hover i { transform: translateY(3px); }

    @media (max-width: 768px) {
        .comments-section { padding: 20px; }
        .comment-form { padding: 18px; }
        .comment-replies { margin-right: 15px; padding-right: 15px; }
    }

    /* ─── Sidebar ─────────────────────────────────────────────────── */
    .article-sidebar { display: flex; flex-direction: column; gap: 25px; position: sticky; top: 90px; }

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

<div class="page-banner" style="margin-right: 3%; margin-top: 3%;">
    <div class="page-banner-inner">
        <h1><i class="fas fa-newspaper" style="color:var(--gold-main);margin-left:12px;"></i>مقاله حقوقی</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('articles.index') }}">مقالات</a>
            <i class="fas fa-chevron-right"></i>
            @if($article->category)
                <a href="{{ route('articles.index', ['cat' => $article->category]) }}">{{ $article->category }}</a>
                <i class="fas fa-chevron-right"></i>
            @endif
            <span>{{ Str::limit($article->title, 40) }}</span>
        </div>
    </div>
</div>

<div class="article-page">
    <div class="article-layout">

        <main class="article-main">

            <div class="article-header">

                @if($article->category)
                    <div class="article-category-badge">
                        <i class="fas fa-folder"></i>
                        <a href="{{ route('articles.index', ['cat' => $article->category]) }}"
                           style="color:inherit;text-decoration:none;">
                            {{ $article->category }}
                        </a>
                    </div>
                @endif

                <h1 class="article-title">{{ $article->title }}</h1>

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

                @if($article->featured_image)
                    <img src="{{ asset('assets/images/' . $article->featured_image) }}"
                         alt="{{ $article->title }}"
                         class="article-featured-img">
                @endif

                @if($article->excerpt)
                    <div class="article-excerpt-box">
                        {{ $article->excerpt }}
                    </div>
                @endif

                <div class="article-body">
                    {!! $article->content !!}
                </div>

                @if($article->tags && count($article->tags) > 0)
                    <div class="article-tags">
                        <i class="fas fa-tags" style="color:var(--gold-main);margin-left:5px;"></i>
                        @foreach($article->tags as $tag)
                            <span class="tag-item">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- ری‌اکشن‌ها --}}
            <div class="reactions-section">
                <span class="reactions-label">این مقاله را چطور ارزیابی می‌کنید؟</span>
                <div class="reactions-grid">
                    @foreach(\App\Models\ArticleReaction::TYPES as $type => $label)
                        <button class="reaction-btn {{ $userReaction === $type ? 'active' : '' }}"
                                data-type="{{ $type }}"
                                onclick="submitReaction('{{ $type }}')">
                            @switch($type)
                                @case('like')      <i class="fas fa-heart"></i>      @break
                                @case('dislike')   <i class="fas fa-thumbs-down"></i> @break
                                @case('helpful')   <i class="fas fa-star"></i>        @break
                                @case('insightful')<i class="fas fa-lightbulb"></i>   @break
                            @endswitch
                            <span>{{ $label }}</span>
                            <b id="count-{{ $type }}">{{ $reactionCounts[$type] ?? 0 }}</b>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- کامنت‌ها --}}
            <div class="comments-section">
                <h3 class="comments-title">
                    <i class="fas fa-comments"></i>
                    نظرات ({{ $comments->count() }})
                </h3>

                @auth
                    <form class="comment-form" id="comment-form" onsubmit="submitComment(event)">
                        @csrf
                        <input type="hidden" name="parent_id" id="parent_id" value="">

                        {{-- ✅ FIX: ID‌ها دقیقاً همان‌هایی هستند که JS استفاده می‌کند --}}
                        <div class="reply-indicator" id="reply-indicator" style="display: none;">
                            <span>در حال پاسخ به <strong id="reply-to-name"></strong></span>
                            <button type="button" onclick="cancelReply()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <textarea name="body"
                                id="comment-body"
                                placeholder="نظر خود را بنویسید..."
                                rows="4"
                                required></textarea>

                        <button type="submit" class="submit-comment-btn">
                            <i class="fas fa-paper-plane"></i>
                            ارسال نظر
                        </button>
                    </form>
                @else
                    <div class="login-prompt">
                        <i class="fas fa-lock"></i>
                        برای ثبت نظر لطفاً <a href="{{ route('login') }}">وارد شوید</a>
                    </div>
                @endauth

                <div class="comments-list" id="comments-list">
                    @php
                        $visibleCount = 3;
                        $totalComments = $comments->count();
                    @endphp

                    @foreach($comments as $index => $comment)
                        <div class="{{ $index >= $visibleCount ? 'hidden-comment' : '' }}">
                            @include('public.articles._comment', ['comment' => $comment, 'depth' => 0])
                        </div>
                    @endforeach

                    @if($totalComments > $visibleCount)
                        <button class="show-more-comments" id="show-more-btn" onclick="showAllComments()">
                            <i class="fas fa-chevron-down"></i>
                            نمایش {{ $totalComments - $visibleCount }} نظر دیگر
                        </button>
                    @endif
                </div>
            </div>

        </main>

        <aside class="article-sidebar">

            @if($article->lawyer)
                <div class="lawyer-card-s">
                    <div class="lawyer-card-s-header">
                        <div class="lawyer-avatar-s">
                            @if($article->lawyer->image)
                                <img src="{{ $article->lawyer->image_url }}"
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

            @if($related->isNotEmpty())
                <div class="related-box">
                    <h3><i class="fas fa-layer-group"></i> مقالات مرتبط</h3>
                    @foreach($related as $rel)
                        <a href="{{ route('articles.show', $rel->slug) }}" class="related-item">
                            @if($rel->featured_image)
                                <img src="{{ asset('assets/images/' . $rel->featured_image) }}"
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
// ─── ری‌اکشن ────────────────────────────────────────────────
function submitReaction(type) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    fetch('{{ route("articles.reactions.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            article_id: {{ $article->id }},
            type: type
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Object.entries(data.counts).forEach(([t, count]) => {
                const el = document.getElementById('count-' + t);
                if (el) el.textContent = count;
            });

            document.querySelectorAll('.reaction-btn').forEach(btn => {
                btn.classList.remove('active');
                if (data.action !== 'removed' && btn.dataset.type === type) {
                    btn.classList.add('active');
                }
            });
        }
    })
    .catch(err => console.error('خطا در ثبت ری‌اکشن:', err));
}

// ─── Reply ──────────────────────────────────────────────────
function replyTo(commentId, authorName) {
    document.getElementById('parent_id').value = commentId;
    document.getElementById('reply-to-name').textContent = authorName;
    document.getElementById('reply-indicator').style.display = 'flex';
    document.getElementById('comment-body').focus();

    document.getElementById('comment-form').scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

function cancelReply() {
    document.getElementById('parent_id').value = '';
    document.getElementById('reply-indicator').style.display = 'none';
    document.getElementById('reply-to-name').textContent = '';
}

// ─── Submit Comment ──────────────────────────────────────────
function submitComment(e) {
    e.preventDefault();

    const body = document.getElementById('comment-body').value.trim();
    const parentId = document.getElementById('parent_id').value;
    const btn = document.querySelector('.submit-comment-btn');

    if (!body) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ارسال...';

    fetch('{{ route("articles.comments.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            article_id: {{ $article->id }},
            parent_id: parentId || null,
            body: body
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('comment-body').value = '';
            cancelReply();
            showAlert('دیدگاه شما پس از تأیید نمایش داده می‌شود 🙂', 'success');
        } else {
            const msg = data.message || 'خطایی رخ داد';
            showAlert(msg, 'error');
        }
    })
    .catch(() => showAlert('خطا در ارسال دیدگاه', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> ارسال نظر';
    });
}

// ─── ✅ FIX: Delete Comment ──────────────────────────────────
function deleteComment(commentId) {
    if (!confirm('آیا از حذف این دیدگاه مطمئن هستید؟')) return;

    fetch(`/articles/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const el = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (el) {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
            showAlert('دیدگاه حذف شد.', 'success');
        } else {
            showAlert(data.message || 'خطا در حذف دیدگاه', 'error');
        }
    })
    .catch(() => showAlert('خطا در ارتباط با سرور', 'error'));
}

// ─── Show All Comments ───────────────────────────────────────
function showAllComments() {
    document.querySelectorAll('.hidden-comment').forEach(el => {
        el.classList.remove('hidden-comment');
    });
    const btn = document.getElementById('show-more-btn');
    if (btn) btn.style.display = 'none';
}

// ─── Share ───────────────────────────────────────────────────
function shareArticle(platform) {
    const url  = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(@json($article->title));
    let link;
    if (platform === 'telegram') link = `https://t.me/share/url?url=${url}&text=${text}`;
    if (platform === 'whatsapp') link = `https://wa.me/?text=${text}%20${url}`;
    window.open(link, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.querySelector('.share-btn.copy i');
        btn.className = 'fas fa-check';
        setTimeout(() => { btn.className = 'fas fa-link'; }, 2000);
    });
}

// ─── Alert ──────────────────────────────────────────────────
function showAlert(message, type) {
    const existing = document.getElementById('commentAlert');
    if (existing) existing.remove();

    const alert = document.createElement('div');
    alert.id = 'commentAlert';
    alert.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: ${type === 'success' ? '#2ecc71' : '#e74c3c'};
        color: #fff;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        z-index: 9999;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        animation: slideUp 0.3s ease;
    `;
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3500);
}
</script>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateX(-50%) translateY(20px); }
    to   { opacity: 1; transform: translateX(-50%) translateY(0); }
}
</style>
@endpush

@endsection