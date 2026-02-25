<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Jalalian;

/**
 * مدل پرونده — نام LegalCase چون Case کلمه رزرو PHP هست
 * جدول: cases
 */
class LegalCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'user_id',
        'lawyer_id',
        'service_id',
        'title',
        'description',
        'current_status',
        'total_fee',
        'paid_amount',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'opened_at'  => 'datetime',
            'closed_at'  => 'datetime',
            'total_fee'  => 'decimal:0',
            'paid_amount' => 'decimal:0',
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(CaseStatusLog::class, 'case_id')->latest('status_date');
    }

    public function documents()
    {
        return $this->hasMany(CaseDocument::class, 'case_id');
    }

    public function installments()
    {
        return $this->hasMany(CaseInstallment::class, 'case_id')->orderBy('installment_number');
    }

    public function pendingInstallments()
    {
        return $this->hasMany(CaseInstallment::class, 'case_id')->where('status', 'pending');
    }

    public function conversation()
    {
        return $this->hasOne(ChatConversation::class, 'case_id');
    }


    public function getProgressPercentAttribute(): int
    {
        if ($this->total_fee == 0) return 0;
        return (int) round(($this->paid_amount / $this->total_fee) * 100);
    }

    public function getRemainingFeeAttribute(): float
    {
        return max(0, $this->total_fee - $this->paid_amount);
    }

    public function isActive(): bool
    {
        return $this->current_status === 'active';
    }

    public function isClosed(): bool
    {
        return in_array($this->current_status, ['closed', 'won', 'lost']);
    }

    public static function generateCaseNumber(): string
    {
        $jalaliYear = Jalalian::now()->getYear();
        $count = self::whereYear('created_at', now()->year)->count() + 1;
        return sprintf('LAW-%d-%03d', $jalaliYear, $count);
    }


    public function scopeActive($query)
    {
        return $query->where('current_status', 'active');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForLawyer($query, int $lawyerId)
    {
        return $query->where('lawyer_id', $lawyerId);
    }
}
