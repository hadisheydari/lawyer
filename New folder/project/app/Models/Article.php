<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body',
        'cover_image', 'lawyer_id', 'read_minutes',
        'is_published', 'published_at', 'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'read_minutes'  => 'integer',
        'views'         => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->latest('published_at');
    }

    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        return $category
            ? $query->where('category', $category)
            : $query;
    }
}
