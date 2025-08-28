<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $title عنوان
 * @property string $description توضیحات
 * @property mixed $status وضعیت
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUserId($value)
 * @property int $complainant_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereComplainantId($value)
 * @mixin \Eloquent
 */
class Complaint extends Model
{
    protected $fillable = [
        'complainant_id',
        'receiver_id',
        'title',
        'description',
        'status',
    ];



    public function complainant(): BelongsTo
    {
        return $this->belongsTo(User::class , 'complainant_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class , 'receiver_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class);
    }

}
