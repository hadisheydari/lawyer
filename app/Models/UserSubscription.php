<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'payment_id',
        'started_at',
        'expires_at',
        'status',
        'used_chat_count',
        'used_call_minutes',
        'used_appointment_count',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function canUseChat(): bool
    {
        if (!$this->isActive()) return false;
        if ($this->plan->chat_limit === null) return true;
        return $this->used_chat_count < $this->plan->chat_limit;
    }

    public function canUseCall(): bool
    {
        if (!$this->isActive()) return false;
        if ($this->plan->call_minutes === null) return true;
        return $this->used_call_minutes < $this->plan->call_minutes;
    }

    public function canUseAppointment(): bool
    {
        if (!$this->isActive()) return false;
        if ($this->plan->appointment_limit === null) return true;
        return $this->used_appointment_count < $this->plan->appointment_limit;
    }

    public function incrementChatUsage(): void
    {
        $this->increment('used_chat_count');
    }

    public function incrementCallUsage(int $minutes): void
    {
        $this->increment('used_call_minutes', $minutes);
    }

    public function incrementAppointmentUsage(): void
    {
        $this->increment('used_appointment_count');
    }
}
