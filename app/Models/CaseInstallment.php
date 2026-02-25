<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'user_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'paid_at',
        'payment_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'paid_at'  => 'datetime',
            'amount'   => 'decimal:0',
        ];
    }


    public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }


    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_date->isPast();
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' تومان';
    }


    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')->where('due_date', '<', now());
    }
}
