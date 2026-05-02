<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست نظرات ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        // نظرات مقالاتی که متعلق به این وکیل است
        $query = ArticleComment::with(['user', 'article'])
            ->whereHas('article', fn($q) => $q->where('lawyer_id', $lawyer->id));

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // نظرات pending را ابتدا نشان بده
        $comments = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'pending'  => ArticleComment::whereHas('article', fn($q) => $q->where('lawyer_id', $lawyer->id))->where('status', 'pending')->count(),
            'approved' => ArticleComment::whereHas('article', fn($q) => $q->where('lawyer_id', $lawyer->id))->where('status', 'approved')->count(),
            'rejected' => ArticleComment::whereHas('article', fn($q) => $q->where('lawyer_id', $lawyer->id))->where('status', 'rejected')->count(),
        ];

        return view('lawyer.comments.index', compact('comments', 'stats'));
    }

    // ─── تأیید نظر ───────────────────────────────────────────────────────────
    public function approve(ArticleComment $comment)
    {
        $this->authorizeComment($comment);

        $comment->approve($this->lawyer()->id);

        return back()->with('success', 'نظر تأیید شد.');
    }

    // ─── رد نظر ──────────────────────────────────────────────────────────────
    public function reject(ArticleComment $comment)
    {
        $this->authorizeComment($comment);

        $comment->update(['status' => 'rejected']);

        return back()->with('success', 'نظر رد شد.');
    }

    // ─── تأیید یا رد دسته‌جمعی ───────────────────────────────────────────────
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'   => 'required|in:approve,reject,delete',
            'ids'      => 'required|array',
            'ids.*'    => 'integer|exists:article_comments,id',
        ]);

        $lawyer   = $this->lawyer();
        $comments = ArticleComment::whereIn('id', $request->ids)
            ->whereHas('article', fn($q) => $q->where('lawyer_id', $lawyer->id))
            ->get();

        foreach ($comments as $comment) {
            match ($request->action) {
                'approve' => $comment->approve($lawyer->id),
                'reject'  => $comment->update(['status' => 'rejected']),
                'delete'  => $comment->delete(),
            };
        }

        $count = $comments->count();

        return back()->with('success', "{$count} نظر با موفقیت پردازش شد.");
    }

    // ─── حذف نظر ─────────────────────────────────────────────────────────────
    public function destroy(ArticleComment $comment)
    {
        $this->authorizeComment($comment);

        // حذف پاسخ‌ها هم
        $comment->replies()->delete();
        $comment->delete();

        return back()->with('success', 'نظر حذف شد.');
    }

    // ─── بررسی دسترسی ────────────────────────────────────────────────────────
    private function authorizeComment(ArticleComment $comment): void
    {
        if ($comment->article->lawyer_id !== $this->lawyer()->id) {
            abort(403, 'شما دسترسی به این نظر را ندارید.');
        }
    }
}