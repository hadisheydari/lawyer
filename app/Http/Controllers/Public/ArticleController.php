<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleReaction;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('cat');

        $articles = Article::published()
            ->byCategory($categorySlug)
            ->recent()
            ->with(['lawyer', 'categories'])
            ->paginate(9)
            ->withQueryString();

        $categories = Category::whereHas('articles', fn ($q) => $q->published())
            ->orderBy('name')
            ->get();

        $activeCategory = $categorySlug
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        return view('public.articles.index', compact('articles', 'categories', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $article = Article::with(['lawyer', 'categories'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $cookieKey = 'viewed_article_' . $article->id;

        if (! request()->cookie($cookieKey)) {
            $article->incrementViewCount();
            cookie()->queue($cookieKey, true, 60 * 24);
        }

        $comments = $article->comments()
            ->with([
                'user',
                'replies' => fn ($q) => $q->approved()->with('user')->oldest(),
            ])
            ->roots()
            ->approved()
            ->latest()
            ->get();

        $categoryIds = $article->categories->pluck('id');

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($categoryIds->isNotEmpty(), function ($q) use ($categoryIds) {
                $q->whereHas('categories', fn ($qq) => $qq->whereIn('categories.id', $categoryIds));
            })
            ->with('lawyer')
            ->recent()
            ->take(3)
            ->get();

        $reactionCounts = $article->reactions()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        foreach (ArticleReaction::TYPES as $type => $label) {
            $reactionCounts[$type] = $reactionCounts[$type] ?? 0;
        }

        $userReaction = auth()->check()
            ? $article->reactions()->where('user_id', auth()->id())->value('type')
            : null;

        return view('public.articles.show', compact(
            'article', 'comments', 'related', 'reactionCounts', 'userReaction'
        ));
    }
}