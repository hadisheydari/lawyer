@extends('layouts.lawyer')
@section('title', 'مدیریت نظرات')

@push('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .page-header h2 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:15px; margin-bottom:25px; }
    .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); text-align:center; border-bottom:3px solid transparent; }
    .stat-n { font-size:2rem; font-weight:900; color:var(--navy); display:block; }
    .stat-l { font-size:0.8rem; color:#888; margin-top:4px; display:block; }

    .filter-bar { background:#fff; padding:15px 20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-tab { padding:7px 18px; border-radius:20px; border:1.5px solid #e0e0e0; background:#fff; font-family:'Vazirmatn',sans-serif; font-size:0.84rem; font-weight:600; color:#888; cursor:pointer; text-decoration:none; transition:0.2s; }
    .filter-tab:hover, .filter-tab.active { border-color:var(--navy); background:var(--navy); color:#fff; }

    .bulk-bar { background:#fff; padding:14px 20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.04); margin-bottom:18px; display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .bulk-bar label { font-size:0.85rem; color:#888; font-weight:600; display:flex; align-items:center; gap:6px; cursor:pointer; }
    .bulk-bar label input { accent-color:var(--gold-main); width:16px; height:16px; }
    .btn-bulk { padding:7px 16px; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-weight:700; font-size:0.82rem; cursor:pointer; border:none; display:inline-flex; align-items:center; gap:5px; transition:0.2s; }
    .btn-approve { background:#d1fae5; color:#065f46; }
    .btn-approve:hover { background:#065f46; color:#fff; }
    .btn-reject  { background:#fef3c7; color:#b45309; }
    .btn-reject:hover  { background:#b45309; color:#fff; }
    .btn-delete  { background:#fee2e2; color:#b91c1c; }
    .btn-delete:hover  { background:#b91c1c; color:#fff; }

    .comments-list { display:flex; flex-direction:column; gap:14px; }

    .comment-card { background:#fff; border-radius:12px; padding:20px 22px; box-shadow:0 3px 10px rgba(0,0,0,0.04); border:1px solid #f0f0f0; transition:0.2s; }
    .comment-card:hover { border-color:var(--gold-main); }
    .comment-card.pending  { border-right:4px solid #f59e0b; }
    .comment-card.approved { border-right:4px solid #10b981; }
    .comment-card.rejected { border-right:4px solid #ef4444; }

    .cc-header { display:flex; align-items:center; gap:14px; margin-bottom:12px; }
    .cc-check { accent-color:var(--gold-main); width:16px; height:16px; cursor:pointer; }
    .cc-avatar { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:var(--gold-main); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.95rem; flex-shrink:0; }
    .cc-meta { flex:1; min-width:0; }
    .cc-meta h4 { font-size:0.9rem; font-weight:700; color:var(--navy); margin:0 0 3px; }
    .cc-meta span { font-size:0.75rem; color:#888; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

    .badge { padding:3px 10px; border-radius:20px; font-size:0.72rem; font-weight:700; }
    .badge-pending  { background:#fef3c7; color:#b45309; }
    .badge-approved { background:#d1fae5; color:#065f46; }
    .badge-rejected { background:#fee2e2; color:#b91c1c; }

    .cc-body { font-size:0.9rem; color:#555; line-height:1.8; padding:12px 14px; background:#fdfbf7; border-radius:8px; margin-bottom:12px; }

    .cc-article { font-size:0.78rem; color:#888; display:flex; align-items:center; gap:6px; margin-bottom:10px; }
    .cc-article a { color:var(--gold-dark); font-weight:600; text-decoration:none; }
    .cc-article a:hover { color:var(--gold-main); }

    .cc-reply-badge { background:#ede9fe; color:#6d28d9; padding:3px 8px; border-radius:8px; font-size:0.72rem; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-right:6px; }

    .cc-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .btn-sm { padding:6px 13px; border-radius:7px; font-size:0.78rem; font-weight:700; cursor:pointer; border:none; font-family:'Vazirmatn',sans-serif; display:inline-flex; align-items:center; gap:4px; transition:0.2s; text-decoration:none; }
    .btn-approve-sm { background:#d1fae5; color:#065f46; }
    .btn-approve-sm:hover { background:#065f46; color:#fff; }
    .btn-reject-sm  { background:#fef3c7; color:#b45309; }
    .btn-reject-sm:hover  { background:#b45309; color:#fff; }
    .btn-delete-sm  { background:#fee2e2; color:#b91c1c; }
    .btn-delete-sm:hover  { background:#b91c1c; color:#fff; }
    .btn-view-sm    { background:#f1f5f9; color:var(--navy); }
    .btn-view-sm:hover    { background:var(--navy); color:#fff; }

    .empty-state { text-align:center; padding:70px 20px; color:#aaa; background:#fff; border-radius:14px; }
    .empty-state i { font-size:3rem; display:block; margin-bottom:15px; opacity:0.4; }

    .pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:20px; flex-wrap:wrap; }
    .page-btn { padding:7px 13px; border-radius:8px; border:1px solid #ddd; color:var(--navy); text-decoration:none; font-size:0.85rem; font-weight:600; transition:0.2s; }
    .page-btn:hover, .page-btn.active { background:var(--navy); color:#fff; border-color:var(--navy); }
    .page-btn.disabled { color:#ccc; pointer-events:none; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2><i class="fas fa-comments" style="color:var(--gold-main);margin-left:10px;"></i>مدیریت نظرات</h2>
</div>

<div class="stats-grid">
    <div class="stat-card" style="border-bottom-color:#f59e0b;">
        <span class="stat-n">{{ $stats['pending'] }}</span>
        <span class="stat-l">در انتظار تأیید</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#10b981;">
        <span class="stat-n">{{ $stats['approved'] }}</span>
        <span class="stat-l">تأیید شده</span>
    </div>
    <div class="stat-card" style="border-bottom-color:#ef4444;">
        <span class="stat-n">{{ $stats['rejected'] }}</span>
        <span class="stat-l">رد شده</span>
    </div>
</div>

<div class="filter-bar">
    <a href="{{ route('lawyer.comments.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">همه</a>
    <a href="{{ route('lawyer.comments.index', ['status'=>'pending']) }}" class="filter-tab {{ request('status')==='pending' ? 'active' : '' }}">
        در انتظار @if($stats['pending'] > 0)<span style="background:#f59e0b;color:#fff;padding:1px 6px;border-radius:10px;font-size:0.65rem;margin-right:4px;">{{ $stats['pending'] }}</span>@endif
    </a>
    <a href="{{ route('lawyer.comments.index', ['status'=>'approved']) }}" class="filter-tab {{ request('status')==='approved' ? 'active' : '' }}">تأیید شده</a>
    <a href="{{ route('lawyer.comments.index', ['status'=>'rejected']) }}" class="filter-tab {{ request('status')==='rejected' ? 'active' : '' }}">رد شده</a>
</div>

@if($comments->isNotEmpty())
    <form method="POST" action="{{ route('lawyer.comments.bulk') }}" id="bulkForm">
        @csrf
        <div class="bulk-bar">
            <label>
                <input type="checkbox" id="selectAll" onchange="toggleAll(this)"> انتخاب همه
            </label>
            <button type="submit" name="action" value="approve" class="btn-bulk btn-approve">
                <i class="fas fa-check"></i> تأیید انتخاب‌شده‌ها
            </button>
            <button type="submit" name="action" value="reject" class="btn-bulk btn-reject">
                <i class="fas fa-times"></i> رد انتخاب‌شده‌ها
            </button>
            <button type="submit" name="action" value="delete" class="btn-bulk btn-delete"
                    onclick="return confirm('نظرات انتخاب‌شده حذف شوند؟')">
                <i class="fas fa-trash-alt"></i> حذف انتخاب‌شده‌ها
            </button>
        </div>

        <div class="comments-list">
            @foreach($comments as $comment)
                <div class="comment-card {{ $comment->status }}">
                    <div class="cc-header">
                        <input type="checkbox" name="ids[]" value="{{ $comment->id }}" class="cc-check comment-cb">
                        <div class="cc-avatar">{{ mb_substr($comment->user->name ?? 'ک', 0, 1) }}</div>
                        <div class="cc-meta">
                            <h4>{{ $comment->user->name ?? 'کاربر ناشناس' }}</h4>
                            <span>
                                <i class="far fa-calendar-alt" style="color:var(--gold-main);"></i>
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($comment->created_at)->format('Y/m/d H:i') }}
                                @if($comment->parent_id)
                                    <span class="cc-reply-badge"><i class="fas fa-reply"></i> پاسخ</span>
                                @endif
                            </span>
                        </div>
                        <span class="badge badge-{{ $comment->status }}">
                            @if($comment->status==='pending') در انتظار
                            @elseif($comment->status==='approved') تأیید شده
                            @else رد شده
                            @endif
                        </span>
                    </div>

                    <div class="cc-article">
                        <i class="fas fa-newspaper" style="color:var(--gold-main);"></i>
                        مقاله:
                        <a href="{{ route('lawyer.articles.show', $comment->article) }}">
                            {{ Str::limit($comment->article->title ?? '—', 50) }}
                        </a>
                    </div>

                    <div class="cc-body">{{ $comment->content }}</div>

                    <div class="cc-actions">
                        @if($comment->status !== 'approved')
                            <form method="POST" action="{{ route('lawyer.comments.approve', $comment) }}">
                                @csrf
                                <button type="submit" class="btn-sm btn-approve-sm">
                                    <i class="fas fa-check"></i> تأیید
                                </button>
                            </form>
                        @endif
                        @if($comment->status !== 'rejected')
                            <form method="POST" action="{{ route('lawyer.comments.reject', $comment) }}">
                                @csrf
                                <button type="submit" class="btn-sm btn-reject-sm">
                                    <i class="fas fa-times"></i> رد
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('lawyer.articles.show', $comment->article) }}" class="btn-sm btn-view-sm" target="_blank">
                            <i class="fas fa-external-link-alt"></i> مشاهده مقاله
                        </a>
                        <form method="POST" action="{{ route('lawyer.comments.destroy', $comment) }}"
                              onsubmit="return confirm('این نظر حذف شود؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete-sm">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@else
    <div class="empty-state">
        <i class="fas fa-comments"></i>
        <p>هیچ نظری یافت نشد.</p>
    </div>
@endif

@if($comments->hasPages())
    <div class="pagination-wrap">
        @if($comments->onFirstPage())
            <span class="page-btn disabled">قبلی</span>
        @else
            <a href="{{ $comments->previousPageUrl() }}" class="page-btn">قبلی</a>
        @endif
        @foreach($comments->getUrlRange(1,$comments->lastPage()) as $page => $url)
            @if($page == $comments->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
        @endforeach
        @if($comments->hasMorePages())
            <a href="{{ $comments->nextPageUrl() }}" class="page-btn">بعدی</a>
        @else
            <span class="page-btn disabled">بعدی</span>
        @endif
    </div>
@endif

@push('scripts')
<script>
function toggleAll(cb) {
    document.querySelectorAll('.comment-cb').forEach(c => c.checked = cb.checked);
}
</script>
@endpush

@endsection