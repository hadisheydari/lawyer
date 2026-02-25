<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Lawyer extends Model
{
    protected $fillable = [
        'name', 'slug', 'title', 'bar_number', 'bar_association',
        'bio', 'phone', 'whatsapp', 'email', 'photo',
        'experience_years', 'cases_count', 'satisfaction_percent',
        'specialties', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'specialties'          => 'array',
        'is_active'            => 'boolean',
        'experience_years'     => 'integer',
        'cases_count'          => 'integer',
        'satisfaction_percent' => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────────────

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ─── Accessors ────────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-lawyer.jpg');
    }

    public function getWhatsappLinkAttribute(): string
    {
        $number = preg_replace('/\D/', '', $this->whatsapp ?? $this->phone);
        // تبدیل ۰۹ به ۹۸
        if (str_starts_with($number, '0')) {
            $number = '98' . substr($number, 1);
        }
        return 'https://wa.me/' . $number;
    }
}
