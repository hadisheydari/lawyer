<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lawyer_id',
        'service_id',
        'title',
        'slug',
        'excerpt',
        'content',          // ✅ Fix: was 'body', DB column is 'content'
        'featured_image',
        'status',
        'published_at',
        'view_count',
        'reading_time',
        'category',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'tags'          => 'array',
        'meta_keywords' => 'array',
        'published_at'  => 'datetime',
        'view_count'    => 'integer',
        'reading_time'  => 'integer',
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

    public function scopeByCategory($query, $category)
    {
        return $category
            ? $query->where('category', $category)
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
            ? asset('assets/images/' . $this->featured_image)
            : asset('assets/images/default-article.jpg');
    }

    // ─── Methods ─────────────────────────────────────────────
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}