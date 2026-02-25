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

    /* ─── Pagination ─────────────────────────────────────────────── */
    .pagination-wrap { display: flex; justify-content: center; gap: 8px; margin-top: 50px; }
    .page-btn {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem;
        border: 1.5px solid #e0e0e0; color: var(--text-body);
        transition: 0.3s; text-decoration: none;
    }
    .page-btn:hover, .page-btn.active { background: var(--navy); color: #fff; border-color: var(--navy); }

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

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <span>دسته‌بندی:</span>
        <button class="filter-btn active">همه مقالات</button>
        <button class="filter-btn">حقوق خانواده</button>
        <button class="filter-btn">دعاوی ملکی</button>
        <button class="filter-btn">امور تجاری</button>
        <button class="filter-btn">حقوق کیفری</button>
        <button class="filter-btn">چک و برات</button>
    </div>

    <div class="articles-layout">

        {{-- ─── Featured Article ─── --}}
        <a href="{{ route('articles.show', 'mahrieh-1404') }}" class="article-featured">
            <div class="af-img">
                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=800&q=70" alt="مهریه">
                <span class="af-badge">مقاله ویژه</span>
            </div>
            <div class="af-content">
                <div class="af-meta">
                    <i class="far fa-calendar-alt"></i> ۱۴۰۴/۰۲/۱۲
                    &nbsp;|&nbsp; حقوق خانواده
                    &nbsp;|&nbsp; <i class="fas fa-clock"></i> ۸ دقیقه مطالعه
                </div>
                <h2 class="af-title">راهنمای جامع مطالبه مهریه در سال ۱۴۰۴: شرایط، مراحل و نکات کلیدی</h2>
                <p class="af-excerpt">
                    با تغییر نرخ شاخص بانک مرکزی، ارزش ریالی مهریه به‌طور قابل‌توجهی تغییر کرده است.
                    در این مقاله جامع، تمام جنبه‌های حقوقی مطالبه مهریه، از نحوه محاسبه تا مراحل اجرایی را
                    به زبان ساده بررسی می‌کنیم...
                </p>
                <span class="btn-read">
                    خواندن مقاله <i class="fas fa-arrow-left"></i>
                </span>
            </div>
        </a>

        @php
        $articles = [
            ['slug' => 'sayyadi-check', 'cat' => 'چک و برات', 'date' => '۱۴۰۴/۰۲/۰۵', 'time' => '۵ دقیقه', 'title' => 'قانون جدید چک‌های صیادی و روش رفع سوءاثر', 'excerpt' => 'همه آنچه باید درباره چک‌های بنفش، نحوه پیگیری قضایی و رفع سوءاثر در سیستم بانکی بدانید...', 'img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=70'],
            ['slug' => 'real-estate-guide', 'cat' => 'دعاوی ملکی', 'date' => '۱۴۰۴/۰۱/۲۸', 'time' => '۶ دقیقه', 'title' => 'راهنمای خرید ملک: نکاتی که قبل از امضا باید بدانید', 'excerpt' => 'نکات کلیدی و حیاتی که پیش از امضای هرگونه قرارداد ملکی باید بدانید تا متضرر نشوید...', 'img' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=70'],
            ['slug' => 'dieh-1404', 'cat' => 'حقوق کیفری', 'date' => '۱۴۰۴/۰۱/۱۵', 'time' => '۴ دقیقه', 'title' => 'دیه سال ۱۴۰۴: مبلغ رسمی و نحوه محاسبه', 'excerpt' => 'دیه کامل مرد مسلمان در سال ۱۴۰۴ و نحوه محاسبه انواع دیات جراحات و اعضا به تفصیل...', 'img' => 'https://images.unsplash.com/photo-1589994965851-a8f479c573a9?auto=format&fit=crop&w=600&q=70'],
            ['slug' => 'inheritance-steps', 'cat' => 'ارث و ترکه', 'date' => '۱۴۰۳/۱۲/۲۰', 'time' => '۷ دقیقه', 'title' => 'انحصار وراثت: مراحل، مدارک و هزینه‌ها', 'excerpt' => 'فرآیند گرفتن گواهی انحصار وراثت قدم به قدم، از تهیه مدارک تا صدور گواهی رسمی...', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=70'],
        ];
        @endphp

        @foreach($articles as $a)
        <a href="{{ route('articles.show', $a['slug']) }}" class="article-card-page">
            <div class="ac-img">
                <img src="{{ $a['img'] }}" alt="{{ $a['title'] }}" loading="lazy">
                <span class="ac-cat">{{ $a['cat'] }}</span>
            </div>
            <div class="ac-content">
                <div class="ac-meta">
                    <i class="far fa-calendar-alt"></i> {{ $a['date'] }}
                    <span style="color:#ddd;">|</span>
                    <i class="fas fa-clock"></i> {{ $a['time'] }} مطالعه
                </div>
                <h3 class="ac-title">{{ $a['title'] }}</h3>
                <p class="ac-excerpt">{{ $a['excerpt'] }}</p>
                <div class="ac-footer">
                    <span class="ac-read-more">ادامه مطلب <i class="fas fa-arrow-left"></i></span>
                    <span class="ac-read-time"><i class="fas fa-eye"></i> ۲.۴k بازدید</span>
                </div>
            </div>
        </a>
        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        <a href="#" class="page-btn active">۱</a>
        <a href="#" class="page-btn">۲</a>
        <a href="#" class="page-btn">۳</a>
        <a href="#" class="page-btn"><i class="fas fa-chevron-left"></i></a>
    </div>

</div>
@endsection
