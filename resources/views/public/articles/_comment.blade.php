<div class="comment-item {{ isset($depth) && $depth > 0 ? 'reply' : '' }}" data-comment-id="{{ $comment->id }}">
    <div class="comment-header">
        <div class="comment-author">
            <i class="fas fa-user-circle"></i>
            <strong>{{ $comment->user->name ?? 'کاربر' }}</strong>
        </div>
        <div class="comment-date">
            {{ \Morilog\Jalali\Jalalian::fromCarbon($comment->created_at)->format('Y/m/d') }}
        </div>
    </div>

    <div class="comment-body">
        {{ $comment->content }}
    </div>

    <div class="comment-actions">
        @auth
            {{-- ✅ FIX: فقط کامنت‌های root (depth=0) قابل پاسخ هستند --}}
            @if(!isset($depth) || $depth === 0)
                <button
                    class="comment-reply-btn"
                    onclick="replyTo({{ $comment->id }}, '{{ addslashes($comment->user->name ?? 'کاربر') }}')"
                    type="button"
                >
                    <i class="fas fa-reply"></i>
                    پاسخ
                </button>
            @endif

            {{-- ویرایش / حذف فقط برای صاحب کامنت --}}
            @if(auth()->id() === $comment->user_id && $comment->status === 'pending')
                <button
                    class="comment-reply-btn"
                    style="color:#dc2626;"
                    onclick="deleteComment({{ $comment->id }})"
                    type="button"
                >
                    <i class="fas fa-trash-alt"></i>
                    حذف
                </button>
            @endif
        @endauth
    </div>

    {{-- replies --}}
    @if($comment->replies->isNotEmpty())
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('public.articles._comment', [
                    'comment' => $reply,
                    'depth'   => ($depth ?? 0) + 1
                ])
            @endforeach
        </div>
    @endif
</div>