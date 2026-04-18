<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleComment;
use Exception;
use Illuminate\Http\Request;

class ArticleCommentController extends Controller
{
    // ─── ثبت کامنت جدید ─────────────────────────────────────────
    public function store(Request $request)
    {
        try{
                    $request->validate([
            'article_id' => ['required', 'integer', 'exists:articles,id'],
            'body'       => ['required', 'string', 'min:5', 'max:1000'],
            'parent_id'  => ['nullable', 'integer', 'exists:article_comments,id'],
        ]);

        // اگر parent_id داده شده، بررسی کن متعلق به همین مقاله باشد
        if ($request->parent_id) {
            $parent = ArticleComment::where('id', $request->parent_id)
                ->where('article_id', $request->article_id)
                ->where('status', 'approved')
                ->firstOrFail();
        }

        $comment = ArticleComment::create([
            'article_id' => $request->article_id,
            'user_id'    => auth()->id(),
            'parent_id'  => $request->parent_id ?? null,
            'content'       => $request->body,
            'status'     => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'دیدگاه شما پس از تأیید نمایش داده می‌شود.',
            'comment' => [
                'id'        => $comment->id,
                'body'      => $comment->content,
                'status'    => $comment->status,
                'parent_id' => $comment->parent_id,
            ],
        ], 201);
        } catch (\Exception $e) {

        dd($e);
        }
    }

    // ─── ویرایش کامنت ───────────────────────────────────────────
    public function update(Request $request, ArticleComment $comment)
    {
        // فقط صاحب کامنت می‌تواند ویرایش کند
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'دسترسی غیرمجاز.',
            ], 403);
        }

        // فقط کامنت‌های pending قابل ویرایش هستند
        if ($comment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'کامنت تأیید شده قابل ویرایش نیست.',
            ], 422);
        }

        $request->validate([
            'content' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $comment->update(['content' => $request->body]);

        return response()->json([
            'success' => true,
            'message' => 'دیدگاه ویرایش شد.',
            'comment' => [
                'id'   => $comment->id,
                'body' => $comment->content,
            ],
        ]);
    }

    // ─── حذف کامنت ──────────────────────────────────────────────
    public function destroy(ArticleComment $comment)
    {
        // فقط صاحب کامنت می‌تواند حذف کند
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'دسترسی غیرمجاز.',
            ], 403);
        }

        // اگر پاسخ‌هایی دارد، پاسخ‌ها هم حذف شوند
        $comment->replies()->delete();
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'دیدگاه حذف شد.',
        ]);
    }
}
