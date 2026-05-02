<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── لیست مقالات ─────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $lawyer = $this->lawyer();

        $query = Article::where('lawyer_id', $lawyer->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'published' => Article::where('lawyer_id', $lawyer->id)->where('status', 'published')->count(),
            'draft'     => Article::where('lawyer_id', $lawyer->id)->where('status', 'draft')->count(),
            'archived'  => Article::where('lawyer_id', $lawyer->id)->where('status', 'archived')->count(),
        ];

        return view('lawyer.articles.index', compact('articles', 'stats'));
    }

    // ─── فرم مقاله جدید ──────────────────────────────────────────────────────
    public function create()
    {
        $services = Service::active()->get();
        return view('lawyer.articles.create', compact('services'));
    }

    // ─── ذخیره مقاله جدید ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $lawyer = $this->lawyer();

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:100',
            'excerpt'          => 'nullable|string|max:500',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published',
            'service_id'       => 'nullable|exists:services,id',
            'reading_time'     => 'nullable|integer|min:1',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ], [
            'title.required'   => 'عنوان مقاله الزامی است.',
            'content.required' => 'متن مقاله الزامی است.',
            'content.min'      => 'متن مقاله باید حداقل ۱۰۰ کاراکتر باشد.',
        ]);

        $data = [
            'lawyer_id'        => $lawyer->id,
            'title'            => $request->title,
            'slug'             => $this->generateUniqueSlug($request->title),
            'content'          => $request->content,
            'excerpt'          => $request->excerpt,
            'category'         => $request->category,
            'tags'             => $request->tags ? array_map('trim', explode(',', $request->tags)) : null,
            'status'           => $request->status,
            'service_id'       => $request->service_id,
            'reading_time'     => $request->reading_time ?? $this->estimateReadingTime($request->content),
            'published_at'     => $request->status === 'published' ? now() : null,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'view_count'       => 0,
        ];

        if ($request->hasFile('featured_image')) {
            $file   = $request->file('featured_image');
            $name   = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $name);
            $data['featured_image'] = $name;
        }

        $article = Article::create($data);

        return redirect()->route('lawyer.articles.show', $article)
            ->with('success', 'مقاله با موفقیت ' . ($request->status === 'published' ? 'منتشر' : 'ذخیره') . ' شد.');
    }

    // ─── نمایش مقاله ─────────────────────────────────────────────────────────
    public function show(Article $article)
    {
        $this->authorizeArticle($article);
        return view('lawyer.articles.show', compact('article'));
    }

    // ─── فرم ویرایش مقاله ────────────────────────────────────────────────────
    public function edit(Article $article)
    {
        $this->authorizeArticle($article);
        $services = Service::active()->get();
        return view('lawyer.articles.edit', compact('article', 'services'));
    }

    // ─── به‌روزرسانی مقاله ────────────────────────────────────────────────────
    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:100',
            'excerpt'          => 'nullable|string|max:500',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published,archived',
            'service_id'       => 'nullable|exists:services,id',
            'reading_time'     => 'nullable|integer|min:1',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = [
            'title'            => $request->title,
            'content'          => $request->content,
            'excerpt'          => $request->excerpt,
            'category'         => $request->category,
            'tags'             => $request->tags ? array_map('trim', explode(',', $request->tags)) : null,
            'status'           => $request->status,
            'service_id'       => $request->service_id,
            'reading_time'     => $request->reading_time ?? $this->estimateReadingTime($request->content),
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
        ];

        // اگر الان منتشر می‌شود و قبلاً نشده بود
        if ($request->status === 'published' && $article->status !== 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $file   = $request->file('featured_image');
            $name   = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $name);
            $data['featured_image'] = $name;
        }

        $article->update($data);

        return redirect()->route('lawyer.articles.show', $article)
            ->with('success', 'مقاله به‌روز شد.');
    }

    // ─── حذف مقاله ───────────────────────────────────────────────────────────
    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);
        $article->delete();

        return redirect()->route('lawyer.articles.index')
            ->with('success', 'مقاله حذف شد.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    private function generateUniqueSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $base  = $slug;
        $count = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    private function estimateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, (int) ceil($wordCount / 200)); // ۲۰۰ کلمه در دقیقه
    }

    private function authorizeArticle(Article $article): void
    {
        if ($article->lawyer_id !== $this->lawyer()->id) {
            abort(403, 'شما دسترسی به این مقاله را ندارید.');
        }
    }
}