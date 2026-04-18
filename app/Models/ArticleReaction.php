<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleReaction extends Model
{
    use HasFactory;

    // ری‌اکشن‌ها نیازی به SoftDelete ندارن
    // چون فقط toggle میشن (حذف واقعی)

    protected $fillable = [
        'article_id',
        'user_id',
        'type',
    ];

    // انواع معتبر ری‌اکشن
    const TYPES = [
        'like'       => 'پسندیدم',
        'dislike'    => 'نپسندیدم',
        'helpful'    => 'مفید بود',
        'insightful' => 'آموزنده بود',
    ];

    // ─── Relations ───────────────────────────────────────────

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ─── Static Methods ───────────────────────────────────────

    // toggle: اگه قبلاً ری‌اکشن داده → حذف، وگرنه → ثبت
    public static function toggle(int $articleId, int $userId, string $type): array
    {
        $existing = static::where('article_id', $articleId)
                          ->where('user_id', $userId)
                          ->first();

        if ($existing) {
            // همون نوع → حذف (toggle off)
            if ($existing->type === $type) {
                $existing->delete();
                $action = 'removed';
            } else {
                // نوع متفاوت → آپدیت
                $existing->update(['type' => $type]);
                $action = 'updated';
            }
        } else {
            // ری‌اکشن جدید
            static::create([
                'article_id' => $articleId,
                'user_id'    => $userId,
                'type'       => $type,
            ]);
            $action = 'added';
        }

        // برگردوندن شمارش جدید همه ری‌اکشن‌ها
        $counts = static::where('article_id', $articleId)
                        ->selectRaw('type, count(*) as total')
                        ->groupBy('type')
                        ->pluck('total', 'type');

        return [
            'action' => $action,
            'counts' => $counts,
        ];
    }
}
