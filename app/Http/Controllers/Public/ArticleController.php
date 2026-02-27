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
        $article = Article::published()
            ->where('slug', $slug)
            ->with('lawyer')
            ->firstOrFail();

        // افزایش بازدید
        $article->incrementViewCount();

        // مقالات مرتبط همان دسته
        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->recent()
            ->take(3)
            ->get();

        return view('public.articles.show', compact('article', 'related'));
    }
}