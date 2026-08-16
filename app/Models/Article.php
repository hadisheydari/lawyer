<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lawyer_id',
        'service_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'view_count',
        'reading_time',
        'tags',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published_at' => 'datetime',
        'view_count'   => 'integer',
        'reading_time' => 'integer',
    ];

    // ─── Relations ───────────────────────────────────────────
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(ArticleComment::class)
                    ->whereNull('parent_id')
                    ->where('status', 'approved')
                    ->with('replies')
                    ->latest();
    }

    public function reactions()
    {
        return $this->hasMany(ArticleReaction::class);
    }

    // ─── Scopes ──────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, ?string $categorySlug)
    {
        return $categorySlug
            ? $query->whereHas('categories', fn ($q) => $q->where('slug', $categorySlug))
            : $query;
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    // ─── Accessors ───────────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        return $this->featured_image
            ? asset($this->featured_image)
            : asset('assets/images/default-article.jpg');
    }

    public function getMetaTitleOrDefaultAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getMetaDescriptionOrDefaultAttribute(): string
    {
        return $this->meta_description
            ?: Str::limit(strip_tags($this->excerpt ?: $this->content), 155);
    }

    // ─── Methods ─────────────────────────────────────────────
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}