@extends('layouts.public')
@section('title', 'مقالات حقوقی | ابدالی و جوشقانی')

@push('styles')
<style>
    .articles-page { max-width: 1200px; margin: 0 auto; padding: 70px 20px; }

    /* ─── Filter Bar ─────────────────────────────────────────────── */
    .filter-bar {
        display: flex; gap: 10px; flex-wrap: wrap;
        margin-bottom: 50px; align-items: center;
    }
    .filter-bar span { font-weight: 700; color: var(--text-heading); margin-left: 5px; }
    .filter-btn {
        padding: 8px 20px; border-radius: 30px;
        border: 1.5px solid rgba(0,0,0,0.1);
        color: var(--text-body); font-size: 0.85rem; font-weight: 600;
        cursor: pointer; transition: 0.3s;
        background: #fff; font-family: 'Vazirmatn', sans-serif;
        text-decoration: none; display: inline-block;
    }
    .filter-btn:hover, .filter-btn.active {
        background: var(--navy); color: #fff; border-color: var(--navy);
    }

    /* ─── Layout: Featured + Grid ──────────────────────────────── */
    .articles-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }

    /* کارت بزرگ (featured) */
    .article-featured {
        grid-column: 1 / -1;
        background: #fff; border-radius: 24px;
        overflow: hidden; display: grid; grid-template-columns: 1.2fr 1fr;
        box-shadow: 0 15px 40px rgba(0,0,0,0.07);
        transition: 0.4s; text-decoration: none; color: inherit;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .article-featured:hover { transform: translateY(-5px); border-color: var(--gold-main); }

    .af-img { position: relative; overflow: hidden; min-height: 340px; }
    .af-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .article-featured:hover .af-img img { transform: scale(1.05); }
    .af-badge {
        position: absolute; top: 20px; right: 20px;
        background: var(--gold-main); color: #fff;
        font-size: 0.75rem; font-weight: 700; padding: 5px 14px; border-radius: 30px;
    }

    .af-content { padding: 45px 40px; display: flex; flex-direction: column; justify-content: center; }
    .af-meta { font-size: 0.8rem; color: var(--gold-dark); font-weight: 700; margin-bottom: 12px; }
    .af-title { font-size: 1.5rem; font-weight: 900; color: var(--text-heading); line-height: 1.5; margin-bottom: 18px; }
    .af-excerpt { font-size: 0.9rem; color: var(--text-body); line-height: 1.85; text-align: justify; margin-bottom: 25px; }
    .btn-read {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: #fff; padding: 12px 24px; border-radius: 10px;
        font-weight: 700; font-size: 0.88rem;
        box-shadow: 0 5px 15px rgba(16,42,67,0.2);
        transition: 0.3s; align-self: flex-start;
    }
    .btn-read:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,42,67,0.3); color: #fff; }

    /* کارت‌های معمولی */
    .article-card-page {
        background: #fff; border-radius: 18px;
        overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        transition: 0.4s; border: 1px solid rgba(0,0,0,0.04);
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .article-card-page:hover { transform: translateY(-8px); border-color: var(--gold-main); }

    .ac-img { height: 200px; overflow: hidden; position: relative; }
    .ac-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .article-card-page:hover .ac-img img { transform: scale(1.08); }
    .ac-cat {
        position: absolute; bottom: 15px; right: 15px;
        background: rgba(16,42,67,0.85); color: var(--gold-main);
        font-size: 0.72rem; font-weight: 700; padding: 4px 12px; border-radius: 20px;
        backdrop-filter: blur(5px);
    }
    /* placeholder وقتی عکس نداره */
    .ac-img-placeholder {
        height: 200px; background: linear-gradient(135deg, #0a1c2e, #1e3a5f);
        display: flex; align-items: center; justify-content: center; position: relative;
    }
    .ac-img-placeholder i { font-size: 3rem; color: rgba(207,168,110,0.4); }

    .ac-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .ac-meta { font-size: 0.78rem; color: var(--text-body); margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .ac-title { font-size: 1.05rem; font-weight: 800; color: var(--text-heading); margin-bottom: 10px; line-height: 1.55; transition: 0.3s; }
    .article-card-page:hover .ac-title { color: var(--gold-main); }
    .ac-excerpt { font-size: 0.85rem; color: var(--text-body); line-height: 1.75; margin-bottom: 20px; }
    .ac-footer {
        margin-top: auto; padding-top: 15px;
        border-top: 1px solid #f0ece5;
        display: flex; justify-content: space-between; align-items: center;
    }
    .ac-read-more { color: var(--gold-main); font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 5px; transition: 0.3s; }
    .article-card-page:hover .ac-read-more { gap: 10px; }
    .ac-read-time { font-size: 0.75rem; color: var(--text-body); display: flex; align-items: center; gap: 5px; }

    /* ─── Empty State ────────────────────────────────────────────── */
    .empty-state {
        grid-column: 1 / -1; text-align: center;
        padding: 80px 20px; color: var(--text-body);
    }
    .empty-state i { font-size: 3.5rem; color: var(--gold-main); opacity: 0.4; margin-bottom: 20px; display: block; }
    .empty-state h3 { font-size: 1.3rem; color: var(--text-heading); margin-bottom: 10px; }
    .empty-state p { font-size: 0.9rem; }

    /* ─── Pagination ─────────────────────────────────────────────── */
    .pagination-wrap {
        display: flex; justify-content: center; align-items: center;
        gap: 8px; margin-top: 50px; flex-wrap: wrap;
    }
    .pagination-wrap .page-link {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem;
        border: 1.5px solid #e0e0e0; color: var(--text-body);
        transition: 0.3s; text-decoration: none; background: #fff;
    }
    .pagination-wrap .page-link:hover,
    .pagination-wrap .page-link.active,
    .pagination-wrap span.page-link { background: var(--navy); color: #fff; border-color: var(--navy); }
    .pagination-wrap .page-link.disabled { opacity: 0.4; pointer-events: none; }

    @media (max-width: 900px) {
        .article-featured { grid-template-columns: 1fr; }
        .articles-layout { grid-template-columns: 1fr; }
        .af-content { padding: 30px 25px; }
    }
</style>
@endpush

@section('content')

<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-newspaper" style="color:var(--gold-main);margin-left:12px;"></i>مقالات حقوقی</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>مقالات</span>
        </div>
    </div>
</div>

<div class="articles-page">

    {{-- ─── Filter Bar — dynamic از دیتابیس ──────────────────── --}}
    <div class="filter-bar">
        <span>دسته‌بندی:</span>

        {{-- دکمه «همه» --}}
        <a href="{{ route('articles.index') }}"
           class="filter-btn {{ is_null($category) ? 'active' : '' }}">
            همه مقالات
        </a>

        {{-- دسته‌بندی‌های واقعی از دیتابیس --}}
        @foreach($categories as $cat)
            <a href="{{ route('articles.index', ['cat' => $cat]) }}"
               class="filter-btn {{ $category === $cat ? 'active' : '' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    @if($articles->isEmpty())

        {{-- ─── Empty State ───────────────────────────────────── --}}
        <div class="articles-layout">
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>مقاله‌ای یافت نشد</h3>
                <p>
                    @if($category)
                        در دسته‌بندی «{{ $category }}» هنوز مقاله‌ای منتشر نشده است.
                        <a href="{{ route('articles.index') }}" style="color:var(--gold-main);font-weight:700;">همه مقالات</a>
                    @else
                        هنوز هیچ مقاله‌ای منتشر نشده است.
                    @endif
                </p>
            </div>
        </div>

    @else

        <div class="articles-layout">

            {{-- ─── Featured Article — اولین مقاله به عنوان ویژه ─── --}}
            @php $featured = $articles->first(); @endphp
            <a href="{{ route('articles.show', $featured->slug) }}" class="article-featured">
                <div class="af-img">
                    @if($featured->featured_image)
                        <img src="{{ asset('storage/' . $featured->featured_image) }}"
                             alt="{{ $featured->title }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#0a1c2e,#1e3a5f);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-newspaper" style="font-size:5rem;color:rgba(207,168,110,0.3);"></i>
                        </div>
                    @endif
                    <span class="af-badge">مقاله ویژه</span>
                </div>
                <div class="af-content">
                    <div class="af-meta">
                        @if($featured->published_at)
                            <i class="far fa-calendar-alt"></i>
                            {{ \Morilog\Jalali\Jalalian::fromCarbon($featured->published_at)->format('Y/m/d') }}
                            &nbsp;|&nbsp;
                        @endif
                        @if($featured->category)
                            {{ $featured->category }}
                            &nbsp;|&nbsp;
                        @endif
                        @if($featured->reading_time)
                            <i class="fas fa-clock"></i> {{ $featured->reading_time }} دقیقه مطالعه
                        @endif
                    </div>
                    <h2 class="af-title">{{ $featured->title }}</h2>
                    @if($featured->excerpt)
                        <p class="af-excerpt">{{ $featured->excerpt }}</p>
                    @endif
                    <span class="btn-read">
                        خواندن مقاله <i class="fas fa-arrow-left"></i>
                    </span>
                </div>
            </a>

            {{-- ─── بقیه مقالات ─────────────────────────────────── --}}
            @foreach($articles->slice(1) as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card-page">

                    @if($article->featured_image)
                        <div class="ac-img">
                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                 alt="{{ $article->title }}" loading="lazy">
                            @if($article->category)
                                <span class="ac-cat">{{ $article->category }}</span>
                            @endif
                        </div>
                    @else
                        <div class="ac-img-placeholder">
                            <i class="fas fa-newspaper"></i>
                            @if($article->category)
                                <span class="ac-cat">{{ $article->category }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="ac-content">
                        <div class="ac-meta">
                            @if($article->published_at)
                                <i class="far fa-calendar-alt"></i>
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($article->published_at)->format('Y/m/d') }}
                            @endif
                            @if($article->reading_time)
                                <span style="color:#ddd;">|</span>
                                <i class="fas fa-clock"></i> {{ $article->reading_time }} دقیقه
                            @endif
                        </div>
                        <h3 class="ac-title">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="ac-excerpt">{{ Str::limit($article->excerpt, 100) }}</p>
                        @endif
                        <div class="ac-footer">
                            <span class="ac-read-more">ادامه مطلب <i class="fas fa-arrow-left"></i></span>
                            @if($article->view_count)
                                <span class="ac-read-time">
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($article->view_count) }} بازدید
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach

        </div>

        {{-- ─── Pagination — از Laravel Paginator ─────────────── --}}
        @if($articles->hasPages())
            <div class="pagination-wrap">

                {{-- دکمه قبلی --}}
                @if($articles->onFirstPage())
                    <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
                @else
                    <a href="{{ $articles->previousPageUrl() }}" class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @endif

                {{-- شماره صفحات --}}
                @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                    @if($page == $articles->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- دکمه بعدی --}}
                @if($articles->hasMorePages())
                    <a href="{{ $articles->nextPageUrl() }}" class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @else
                    <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
                @endif

            </div>
        @endif

    @endif

</div>
@endsection