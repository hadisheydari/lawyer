<div class="comment-item {{ isset($depth) && $depth > 0 ? 'reply' : '' }}">
    <div class="comment-header">
        <div class="comment-avatar">
            {{ mb_substr($comment->user->name, 0, 1) }}
        </div>
        <div class="comment-meta">
            <div class="comment-author">{{ $comment->user->name }}</div>
            <div class="comment-date">
                {{ \Morilog\Jalali\Jalalian::fromCarbon($comment->created_at)->format('Y/m/d') }}
            </div>
        </div>
    </div>

    <div class="comment-body">
        {{ $comment->content }} 
    </div>

    @auth
        @if(!isset($depth) || $depth === 0)
            <button
                class="comment-reply-btn"
                onclick="replyTo({{ $comment->id }}, '{{ addslashes($comment->user->name) }}')"
            >
                <i class="fas fa-reply"></i>
                پاسخ
            </button>
        @endif
    @endauth

    {{-- replies --}}
    @if($comment->replies->isNotEmpty())
        @foreach($comment->replies as $reply)
            @include('public.articles._comment', [
                'comment' => $reply,
                'depth'   => ($depth ?? 0) + 1
            ])
        @endforeach
    @endif
</div>
