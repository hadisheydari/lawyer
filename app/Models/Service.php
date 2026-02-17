<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'short_description',
        'description',
        'features',
        'image',
        'is_active',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'meta_keywords' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-service.jpg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
