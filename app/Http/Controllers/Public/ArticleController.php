<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('cat');

        $articles = Article::published()
            ->byCategory($category)
            ->with('lawyer')
            ->paginate(9);

        // دسته‌بندی‌های موجود برای فیلتر
        $categories = Article::published()
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('public.articles.index', compact('articles', 'category', 'categories'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->where('is_published', true)->with('lawyer')->firstOrFail();

        // افزایش بازدید
        $article->increment('views');

        // مقالات مرتبط همان دسته
        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->take(3)
            ->get();

        return view('public.articles.show', compact('article', 'related'));
    }
}
