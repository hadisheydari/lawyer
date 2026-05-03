@extends('layouts.lawyer')
@section('title', Str::limit($article->title, 40))

@push('styles')
<style>
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--gold-dark); font-weight:600; font-size:0.9rem; text-decoration:none; margin-bottom:20px; }
    .back-link:hover { color:var(--gold-main); }

    .article-header { background:linear-gradient(135deg,var(--navy),#1e3a5f); border-radius:16px; padding:28px 32px; color:#fff; margin-bottom:25px; }
    .art-category { font-size:0.78rem; color:rgba(212,175,55,0.8); font-weight:700; margin-bottom:8px; }
    .art-title { font-size:1.5rem; font-weight:900; margin-bottom:12px; line-height:1.4; }
    .art-meta { display:flex; gap:16px; flex-wrap:wrap; font-size:0.8rem; color:rgba(255,255,255,0.6); }
    .art-meta span { display:flex; align-items:center; gap:5px; }

    .badge { padding:5px 14px; border-radius:20px; font-size:0.78rem; font-weight:700; }
    .badge-published { background:rgba(16,185,129,0.2); color:#6ee7b7; border:1px solid rgba(16,185,129,0.3); }
    .badge-draft { background:rgba(245,158,11,0.2); color:#fcd34d; border:1px solid rgba(245,158,11,0.3); }
    .badge-archived { background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.6); border:1px solid rgba(255,255,255,0.2); }

    .grid-2 { display:grid; grid-template-columns:1fr 300px; gap:25px; align-items:start; }

    .card { background:#fff; border-radius:14px; padding:24px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:0.95rem; font-weight:800; color:var(--navy); margin-bottom:16px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .article-content { font-size:0.95rem; line-height:2; color:#374151; }
    .article-content h2 { font-size:1.1rem; font-weight:800; color:var(--navy); margin:20px 0 10px; }
    .article-content h3 { font-size:1rem; font-weight:700; color:var(--navy); margin:16px 0 8px; }
    .article-content p { margin-bottom:14px; }
    .article-content blockquote { border-right:4px solid var(--gold-main); padding:12px 16px; background:#fdfbf7; border-radius:0 8px 8px 0; margin:16px 0; font-style:italic; }
    .article-content code { background:#f1f5f9; padding:2px 6px; border-radius:4px; font-family:monospace; font-size:0.9em; }
    .article-content ul, .article-content ol { padding-right:20px; margin-bottom:14px; }
    .article-content li { margin-bottom:6px; }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f5f5f5; font-size:0.85rem; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:#888; }
    .info-value { font-weight:700; color:var(--navy); }

    .tag-chip { background:rgba(197,160,89,0.1); border:1px solid rgba(197,160,89,0.3); color:var(--gold-dark); padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; display:inline-block; margin:2px; }

    .stat-row { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }
    .stat-item { text-align:center; background:#f8fafc; padding:12px; border-radius:8px; }
    .stat-item .n { font-size:1.2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-item .l { font-size:0.7rem; color:#888; }

    .action-btns { display:flex; flex-direction:column; gap:10px; }
    .btn-act { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px; border-radius:9px; font-weight:700; font-size:0.88rem; text-decoration:none; transition:0.2s; cursor:pointer; border:none; font-family:'Vazirmatn',sans-serif; }
    .btn-edit { background:var(--navy); color:#fff; }
    .btn-edit:hover { opacity:0.9; }
    .btn-pub { background:#d1fae5; color:#065f46; }
    .btn-pub:hover { background:#065f46; color:#fff; }
    .btn-del { background:#fee2e2; color:#b91c1c; border:1.5px solid #fecaca; }
    .btn-del:hover { background:#fef2f2; }
    .btn-pub-link { background:linear-gradient(135deg,var(--gold-main),var(--gold-dark)); color:#fff; }
    .btn-pub-link:hover { opacity:0.9; }

    @media(max-width:960px) { .grid-2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<a href="{{ route('lawyer.articles.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i> بازگشت به مقالات
</a>

<div class="article-header">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
        <div>
            @if($article->category)
                <div class="art-category"><i class="fas fa-tag"></i> {{ $article->category }}</div>
            @endif
            <div class="art-title">{{ $article->title }}</div>
            <div class="art-meta">
                @if($article->published_at)
                    <span><i class="far fa-calendar-alt"></i> {{ \Morilog\Jalali\Jalalian::fromCarbon($article->published_at)->format('Y/m/d') }}</span>
                @endif
                <span><i class="far fa-eye"></i> {{ number_format($article->view_count) }} بازدید</span>
                <span><i class="far fa-clock"></i> {{ $article->reading_time }} دقیقه مطالعه</span>
            </div>
        </div>
        @php $sc = ['published'=>'badge-published','draft'=>'badge-draft','archived'=>'badge-archived'][$article->status] ?? ''; @endphp
        @php $sl = ['published'=>'منتشر شده','draft'=>'پیش‌نویس','archived'=>'آرشیو'][$article->status] ?? $article->status; @endphp
        <span class="badge {{ $sc }}">{{ $sl }}</span>
    </div>
</div>

<div class="grid-2">

    {{-- محتوا --}}
    <div>
        @if($article->featured_image)
            <div class="card" style="padding:0;overflow:hidden;">
                <img src="{{ asset('assets/images/'.$article->featured_image) }}" alt="{{ $article->title }}"
                     style="width:100%;max-height:350px;object-fit:cover;">
            </div>
        @endif

        @if($article->excerpt)
            <div class="card" style="background:linear-gradient(135deg,rgba(197,160,89,0.08),rgba(197,160,89,0.03));border-right:4px solid var(--gold-main);">
                <p style="font-size:0.95rem;color:#555;margin:0;line-height:1.9;font-style:italic;">{{ $article->excerpt }}</p>
            </div>
        @endif

        <div class="card">
            <div class="card-title"><i class="fas fa-align-right"></i> متن مقاله</div>
            <div class="article-content">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>

        @if($article->tags && count($article->tags))
            <div class="card">
                <div class="card-title"><i class="fas fa-tags"></i> تگ‌ها</div>
                <div>
                    @foreach($article->tags as $tag)
                        <span class="tag-chip">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- سایدبار --}}
    <div>
        <div class="card">
            <div class="card-title"><i class="fas fa-chart-bar"></i> آمار</div>
            <div class="stat-row">
                <div class="stat-item">
                    <span class="n">{{ number_format($article->view_count) }}</span>
                    <span class="l">بازدید</span>
                </div>
                <div class="stat-item">
                    <span class="n">{{ $article->comments()->where('status','approved')->count() }}</span>
                    <span class="l">نظر</span>
                </div>
                <div class="stat-item">
                    <span class="n">{{ $article->reactions()->count() }}</span>
                    <span class="l">واکنش</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-info-circle"></i> اطلاعات</div>
            <div class="info-row"><span class="info-label">خدمت</span><span class="info-value">{{ $article->service->title ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">دسته‌بندی</span><span class="info-value">{{ $article->category ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">تاریخ ایجاد</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($article->created_at)->format('Y/m/d') }}</span></div>
            @if($article->updated_at != $article->created_at)
                <div class="info-row"><span class="info-label">آخرین ویرایش</span><span class="info-value">{{ \Morilog\Jalali\Jalalian::fromCarbon($article->updated_at)->format('Y/m/d') }}</span></div>
            @endif
            @if($article->meta_title)
                <div class="info-row"><span class="info-label">عنوان متا</span><span class="info-value" style="font-size:0.8rem;">{{ Str::limit($article->meta_title, 30) }}</span></div>
            @endif
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-bolt"></i> عملیات</div>
            <div class="action-btns">
                <a href="{{ route('lawyer.articles.edit', $article) }}" class="btn-act btn-edit">
                    <i class="fas fa-edit"></i> ویرایش مقاله
                </a>
                @if($article->status === 'published')
                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="btn-act btn-pub-link">
                        <i class="fas fa-external-link-alt"></i> مشاهده در سایت
                    </a>
                @endif
                <a href="{{ route('lawyer.comments.index') }}" class="btn-act btn-pub">
                    <i class="fas fa-comments"></i> نظرات
                    @php $pComments = $article->comments()->where('status','pending')->count(); @endphp
                    @if($pComments > 0)
                        <span style="background:#ef4444;color:#fff;padding:1px 7px;border-radius:20px;font-size:0.65rem;">{{ $pComments }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('lawyer.articles.destroy', $article) }}"
                      onsubmit="return confirm('این مقاله حذف شود؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-act btn-del" style="width:100%;">
                        <i class="fas fa-trash-alt"></i> حذف مقاله
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection