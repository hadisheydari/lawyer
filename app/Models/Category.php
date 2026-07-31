<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'meta_description'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // اسلاگ فارسی سالم (بدون حذف حروف فارسی که Str::slug انجام می‌دهد)
    public static function makeSlug(string $name): string
    {
        $slug = trim(preg_replace('/\s+/u', '-', trim($name)));
        $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);

        return trim($slug, '-') ?: Str::random(6);
    }
}