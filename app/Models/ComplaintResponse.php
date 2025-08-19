<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $complaint_id
 * @property int $responder_id
 * @property string $message متن پاسخ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereResponderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplaintResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ComplaintResponse extends Model
{
    protected $fillable = [
        'complaint_id',
        'responder_id',
        'message',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }
}
