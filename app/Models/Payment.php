<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payable_type',
        'payable_id',
        'tracking_code',
        'authority',
        'ref_id',
        'amount',
        'status',
        'gateway',
        'description',
        'gateway_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gateway_response' => 'array',
            'paid_at'          => 'datetime',
            'amount'           => 'decimal:0',
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * payable می‌تونه:
     *   - Consultation     (مشاوره مشتری ساده)
     *   - CaseInstallment  (قسط پرونده موکل ویژه)
     */
    public function payable()
    {
        return $this->morphTo();
    }


    public function isPaid(): bool     { return $this->status === 'paid'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isFailed(): bool   { return $this->status === 'failed'; }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' تومان';
    }

    public static function generateTrackingCode(): string
    {
        return 'LAW-' . strtoupper(uniqid());
    }


    public function scopePaid($query)    { return $query->where('status', 'paid'); }
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeFailed($query)  { return $query->where('status', 'failed'); }
}
