<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            'tags'          => 'array',
            'meta_keywords' => 'array',
            'published_at'  => 'datetime',
        ];
    }


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
            ->where('status', 'approved')
            ->whereNull('parent_id');
    }

    public function reactions()
    {
        return $this->hasMany(ArticleReaction::class);
    }


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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }
}
