<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $code کد اعتبارسنجی
 * @property Carbon|null $expires_at اعتبار کد
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $remaining_seconds
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUserId($value)
 *
 * @mixin \Eloquent
 */
class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otps';

    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon && $this->expires_at->isPast();
    }

    public function getRemainingSecondsAttribute(): ?int
    {
        return $this->expires_at ? now()->diffInSeconds($this->expires_at, false) : null;
    }
}
