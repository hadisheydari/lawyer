<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
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

    public function index(Request $request)
    {
        $lawyer = $this->lawyer();
        $query  = Article::where('lawyer_id', $lawyer->id)->with('categories');

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

    public function create()
    {
        $services   = Service::active()->get();
        $categories = Category::orderBy('name')->get();

        return view('lawyer.articles.create', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        $lawyer = $this->lawyer();

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:100',
            'excerpt'          => 'nullable|string|max:500',
            'category_ids'     => 'nullable|array',
            'category_ids.*'   => 'exists:categories,id',
            'new_categories'   => 'nullable|array',
            'new_categories.*' => 'string|max:100',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:50',
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
            'tags'             => $request->tags ?: null,
            'status'           => $request->status,
            'service_id'       => $request->service_id,
            'reading_time'     => $request->reading_time ?? $this->estimateReadingTime($request->content),
            'published_at'     => $request->status === 'published' ? now() : null,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'view_count'       => 0,
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request->file('featured_image'), $request->title);
        }

        $article = Article::create($data);
        $this->syncCategories($article, $request->category_ids ?? [], $request->new_categories ?? []);

        return redirect()->route('lawyer.articles.show', $article)
            ->with('success', 'مقاله با موفقیت ' . ($request->status === 'published' ? 'منتشر' : 'ذخیره') . ' شد.');
    }
    private function syncCategories(Article $article, array $categoryIds = [], array $newCategoryNames = []): void
    {
        $ids = array_map('intval', $categoryIds);

        foreach ($newCategoryNames as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }

            $category = Category::firstOrCreate(
                ['slug' => Category::makeSlug($name)],
                ['name' => $name]
            );
            $ids[] = $category->id;
        }

        $article->categories()->sync(array_unique($ids));
    }

    public function show(Article $article)
    {
        $this->authorizeArticle($article);
        $article->load('categories');

        return view('lawyer.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $this->authorizeArticle($article);
        $services   = Service::active()->get();
        $categories = Category::orderBy('name')->get();
        $article->load('categories');

        return view('lawyer.articles.edit', compact('article', 'services', 'categories'));
    }


    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string|min:100',
            'excerpt'          => 'nullable|string|max:500',
            'category_ids'     => 'nullable|array',
            'category_ids.*'   => 'exists:categories,id',
            'new_categories'   => 'nullable|array',
            'new_categories.*' => 'string|max:100',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:50',
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
            'tags'             => $request->tags ?: null,
            'status'           => $request->status,
            'service_id'       => $request->service_id,
            'reading_time'     => $request->reading_time ?? $this->estimateReadingTime($request->content),
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
        ];

        if ($request->status === 'published' && $article->status !== 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteFeaturedImage($article->featured_image);
            $data['featured_image'] = $this->storeFeaturedImage($request->file('featured_image'), $request->title);
        }

        $article->update($data);
        $this->syncCategories($article, $request->category_ids ?? [], $request->new_categories ?? []);

        return redirect()->route('lawyer.articles.show', $article)
            ->with('success', 'مقاله به‌روز شد.');
    }


    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);
        $this->deleteFeaturedImage($article->featured_image);
        $article->delete();

        return redirect()->route('lawyer.articles.index')
            ->with('success', 'مقاله حذف شد.');
    }


    // اسلاگ فارسی سالم — حروف فارسی حذف نمی‌شوند (برای سئوی فارسی بهتر است)
    private function generateUniqueSlug(string $title): string
    {
        $slug = trim(preg_replace('/\s+/u', '-', trim($title)));
        $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);
        $slug = trim($slug, '-') ?: Str::random(8);

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
        return max(1, (int) ceil($wordCount / 200));
    }

    private function authorizeArticle(Article $article): void
    {
        if ($article->lawyer_id !== $this->lawyer()->id) {
            abort(403, 'شما دسترسی به این مقاله را ندارید.');
        }
    }


    private function storeFeaturedImage($file, string $title): string
    {
        $name = time() . '_' . Str::slug($title, '-') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/images'), $name);

        return 'assets/images/' . $name; // مسیر کامل ذخیره می‌شود
    }

    private function deleteFeaturedImage(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
