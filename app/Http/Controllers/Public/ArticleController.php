<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleReaction;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('cat');

        $articles = Article::published()
            ->byCategory($category)
            ->recent()
            ->with('lawyer')
            ->paginate(9);

        // دسته‌بندی‌های موجود برای فیلتر — از مقالات published واقعی
        $categories = Article::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.articles.index', compact('articles', 'category', 'categories'));
    }

    public function show(string $slug)
    {
        $article = Article::with(['lawyer']) // ✅ فقط lawyer که رابطه واقعیه
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        // ✅ جلوگیری از شمارش تکراری با کوکی
        $cookieKey = 'viewed_article_'.$article->id;

        if (! request()->cookie($cookieKey)) {
            $article->incrementViewCount();
            cookie()->queue($cookieKey, true, 60 * 24);
        }

        // ─── کامنت‌های تایید شده ─────────────────────────
        $comments = $article->comments()
            ->with([
                'user',
                'replies' => fn ($q) => $q->approved()
                    ->with('user')
                    ->oldest(),
            ])
            ->roots()
            ->approved()
            ->latest()
            ->get();

        // ─── مقالات مرتبط ─────────────────────────────────
        $related = Article::published()
            ->byCategory($article->category)
            ->where('id', '!=', $article->id)
            ->with('lawyer')
            ->recent()
            ->take(3)
            ->get();

        // ─── ری‌اکشن‌های جمع‌بندی شده ──────────────────────
        $reactionCounts = $article->reactions()
            ->Raw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        foreach (ArticleReaction::TYPES as $type => $label) {
            if (! isset($reactionCounts[$type])) {
                $reactionCounts[$type] = 0;
            }
        }

        // ─── ری‌اکشن کاربر لاگین شده ────────────────────────
        $userReaction = auth()->check()
            ? $article->reactions()->where('user_id', auth()->id())->value('type')
            : null;

        return view('public.articles.show', compact(
            'article',
            'comments',
            'related',
            'reactionCounts',
            'userReaction'
        ));
    }
}
