<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'content',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
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

    // کامنت والد
    public function parent()
    {
        return $this->belongsTo(ArticleComment::class, 'parent_id');
    }

    // کامنت‌های فرزند (یه سطح)
    public function replies()
    {
        return $this->hasMany(ArticleComment::class, 'parent_id')
                    ->where('status', 'approved')
                    ->with('user')
                    ->latest();
    }

    // وکیلی که تأیید کرده
    public function approvedByLawyer()
    {
        return $this->belongsTo(Lawyer::class, 'approved_by');
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRoots($query)
    {
        // فقط کامنت‌های اصلی (نه reply)
        return $query->whereNull('parent_id');
    }

    // ─── Methods ─────────────────────────────────────────────

    public function approve(int $lawyerId): void
    {
        $this->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => $lawyerId,
        ]);
    }

    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}
