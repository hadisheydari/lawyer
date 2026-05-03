@extends('layouts.lawyer')
@section('title', 'مقالات')

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--navy);
            margin: 0;
        }

        .btn-new {
            background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
            color: var(--navy);
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-new:hover {
            transform: translateY(-2px);
            color: var(--navy);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            text-align: center;
        }

        .stat-n {
            font-size: 2rem;
            font-weight: 900;
            color: var(--navy);
            display: block;
        }

        .stat-l {
            font-size: 0.8rem;
            color: #888;
            margin-top: 4px;
            display: block;
        }

        .filter-bar {
            background: #fff;
            padding: 18px 22px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 9px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 0.88rem;
            outline: none;
            transition: 0.2s;
        }

        .filter-bar input {
            flex: 1;
            min-width: 180px;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            border-color: var(--gold-main);
        }

        .btn-filter {
            background: var(--navy);
            color: #fff;
            padding: 9px 18px;
            border: none;
            border-radius: 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
        }

        .article-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            transition: 0.3s;
        }

        .article-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold-main);
        }

        .ac-image {
            height: 160px;
            background: linear-gradient(135deg, var(--navy), #1e3a5f);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .ac-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ac-image-placeholder {
            font-size: 3rem;
            color: rgba(212, 175, 55, 0.3);
        }

        .ac-status {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-published {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-draft {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-archived {
            background: #f1f5f9;
            color: #64748b;
        }

        .ac-body {
            padding: 18px;
        }

        .ac-cat {
            font-size: 0.72rem;
            color: var(--gold-dark);
            font-weight: 700;
            margin-bottom: 6px;
        }

        .ac-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ac-meta {
            font-size: 0.75rem;
            color: #888;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .ac-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ac-footer {
            display: flex;
            gap: 8px;
            justify-content: space-between;
            align-items: center;
        }

        .btn-sm {
            padding: 6px 13px;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: 0.2s;
        }

        .btn-view {
            background: #f1f5f9;
            color: var(--navy);
        }

        .btn-view:hover {
            background: var(--navy);
            color: #fff;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .btn-del {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            cursor: pointer;
            font-family: 'Vazirmatn', sans-serif;
        }

        .btn-del:hover {
            background: #b91c1c;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 70px 20px;
            color: #aaa;
            background: #fff;
            border-radius: 14px;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 15px;
            opacity: 0.4;
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 7px 13px;
            border-radius: 8px;
            border: 1px solid #ddd;
            color: var(--navy);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .page-btn:hover,
        .page-btn.active {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }

        .page-btn.disabled {
            color: #ccc;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <h2><i class="fas fa-newspaper" style="color:var(--gold-main);margin-left:10px;"></i>مقالات</h2>
        <a href="{{ route('lawyer.articles.create') }}" class="btn-new">
            <i class="fas fa-plus"></i> مقاله جدید
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-bottom:3px solid #10b981;">
            <span class="stat-n">{{ $stats['published'] }}</span><span class="stat-l">منتشرشده</span>
        </div>
        <div class="stat-card" style="border-bottom:3px solid #f59e0b;">
            <span class="stat-n">{{ $stats['draft'] }}</span><span class="stat-l">پیش‌نویس</span>
        </div>
        <div class="stat-card" style="border-bottom:3px solid #64748b;">
            <span class="stat-n">{{ $stats['archived'] }}</span><span class="stat-l">آرشیو</span>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <input type="text" name="search" placeholder="جستجو عنوان مقاله..." value="{{ request('search') }}">
        <select name="status">
            <option value="">همه وضعیت‌ها</option>
            <option value="published" @selected(request('status') === 'published')>منتشرشده</option>
            <option value="draft" @selected(request('status') === 'draft')>پیش‌نویس</option>
            <option value="archived" @selected(request('status') === 'archived')>آرشیو</option>
        </select>
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> جستجو</button>
    </form>

    @if ($articles->isNotEmpty())
        <div class="articles-grid">
            @foreach ($articles as $article)
                <div class="article-card">
                    <div class="ac-image">
                        @if ($article->featured_image)
                            <img src="{{ asset('assets/images/' . $article->featured_image) }}" alt="{{ $article->title }}">
                        @else
                            <div class="ac-image-placeholder"><i class="fas fa-newspaper"></i></div>
                        @endif
                        <div class="ac-status">
                            @php $sc = ['published'=>'badge-published','draft'=>'badge-draft','archived'=>'badge-archived'][$article->status] ?? ''; @endphp
                            @php $sl = ['published'=>'منتشر','draft'=>'پیش‌نویس','archived'=>'آرشیو'][$article->status] ?? $article->status; @endphp
                            <span class="badge {{ $sc }}">{{ $sl }}</span>
                        </div>
                    </div>
                    <div class="ac-body">
                        @if ($article->category)
                            <div class="ac-cat"><i class="fas fa-tag"></i> {{ $article->category }}</div>
                        @endif
                        <div class="ac-title">{{ $article->title }}</div>
                        <div class="ac-meta">
                            @if ($article->published_at->year > 1900)
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($article->published_at)->format('Y/m/d') }}
                            @else
                                {{ $article->published_at->format('Y/m/d') }} (نیاز به اصلاح)
                            @endif {{ $article->published_at->format('Y/m/d') }}

                            <span><i class="far fa-eye"></i> {{ number_format($article->view_count) }} بازدید</span>
                            <span><i class="far fa-clock"></i> {{ $article->reading_time }} دقیقه</span>
                        </div>
                        <div class="ac-footer">
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('lawyer.articles.show', $article) }}" class="btn-sm btn-view">
                                    <i class="fas fa-eye"></i> مشاهده
                                </a>
                                <a href="{{ route('lawyer.articles.edit', $article) }}" class="btn-sm btn-edit">
                                    <i class="fas fa-edit"></i> ویرایش
                                </a>
                            </div>
                            <form method="POST" action="{{ route('lawyer.articles.destroy', $article) }}"
                                onsubmit="return confirm('حذف شود؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-del">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <p>هیچ مقاله‌ای یافت نشد.</p>
            <a href="{{ route('lawyer.articles.create') }}" class="btn-new" style="display:inline-flex;margin-top:15px;">
                <i class="fas fa-plus"></i> اولین مقاله را بنویسید
            </a>
        </div>
    @endif

    @if ($articles->hasPages())
        <div class="pagination-wrap">
            @if ($articles->onFirstPage())
                <span class="page-btn disabled">قبلی</span>
            @else
                <a href="{{ $articles->previousPageUrl() }}" class="page-btn">قبلی</a>
            @endif
            @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                @if ($page == $articles->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
            @if ($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}" class="page-btn">بعدی</a>
            @else
                <span class="page-btn disabled">بعدی</span>
            @endif
        </div>
    @endif

@endsection
