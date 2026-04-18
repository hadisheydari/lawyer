<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ArticleReaction;
use Illuminate\Http\Request;

class ArticleReactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => ['required', 'integer', 'exists:articles,id'],
            'type'       => ['required', 'string', 'in:' . implode(',', array_keys(ArticleReaction::TYPES))],
        ]);

        $user = auth()->user();

        $existing = ArticleReaction::where('article_id', $request->article_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ($existing->type === $request->type) {
                // همان ری‌اکشن → حذف (toggle off)
                $existing->delete();
                $action = 'removed';
            } else {
                // ری‌اکشن متفاوت → تغییر
                $existing->update(['type' => $request->type]);
                $action = 'updated';
            }
        } else {
            // ری‌اکشن جدید
            ArticleReaction::create([
                'article_id' => $request->article_id,
                'user_id'    => $user->id,
                'type'       => $request->type,
            ]);
            $action = 'created';
        }

        // شمارش به‌روز ری‌اکشن‌ها
        $counts = ArticleReaction::where('article_id', $request->article_id)
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // پر کردن تایپ‌های صفر
        foreach (array_keys(ArticleReaction::TYPES) as $type) {
            $counts[$type] = $counts[$type] ?? 0;
        }

        return response()->json([
            'success' => true,
            'action'  => $action,
            'counts'  => $counts,
        ]);
    }
}
