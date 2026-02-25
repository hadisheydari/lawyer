<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Reservation extends Model
{
    protected $fillable = [
        'name', 'phone', 'lawyer_id', 'service_id',
        'reserved_date', 'time_slot', 'notes', 'status', 'tracking_code',
    ];

    protected $casts = [
        'reserved_date' => 'date',
    ];

    // ─── Boot ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (self $reservation) {
            if (empty($reservation->tracking_code)) {
                // کد پیگیری ۸ رقمی تصادفی
                $reservation->tracking_code = strtoupper(Str::random(8));
            }
        });
    }

    // ─── Relations ────────────────────────────────────────────────

    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
