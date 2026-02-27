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
        'base_price', // فیلد جدید اضافه شد
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
            'features'      => 'array',
            'meta_keywords' => 'array',
            'is_active'     => 'boolean',
            'base_price'    => 'decimal:0', // کست کردن به عدد صحیح
        ];
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    // ارتباط با پرونده‌ها (اگر خدمتی حذف شد، پرونده‌های مرتبط مشخص باشند)
    public function cases()
    {
        return $this->hasMany(LegalCase::class);
    }

    // ارتباط با مقالات (مثلا مقالات مرتبط با دعوای ملکی)
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    // یک تابع کمکی برای نمایش زیبای قیمت در سایت
    public function getFormattedPriceAttribute(): string
    {
        if (is_null($this->base_price) || $this->base_price == 0) {
            return 'توافقی / پس از بررسی پرونده';
        }
        
        return 'شروع از ' . number_format($this->base_price) . ' تومان';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}