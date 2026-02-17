<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'service_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'status',
        'published_at',
        'view_count',
        'reading_time',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'meta_keywords' => 'array',
            'published_at' => 'datetime',
        ];
    }

    // Relations
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
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
        return $this->hasMany(ArticleComment::class)->where('status', 'approved');
    }

    public function reactions()
    {
        return $this->hasMany(ArticleReaction::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Helpers
    public function getImageUrlAttribute(): string
    {
        return $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : asset('images/default-article.jpg');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getReactionCount(string $type): int
    {
        return $this->reactions()->where('type', $type)->count();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
