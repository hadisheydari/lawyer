<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $cargo_id
 * @property int|null $driver_id
 * @property int|null $weight وزن (تن)
 * @property int|null $fare کرایه
 * @property int|null $commission کمیسیون
 * @property string $status وضعیت حمل
 * @property string|null $havaleFile فایل حواله
 * @property string|null $barnamehFile فایل بارنامه
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo|null $cargo
 * @property-read \App\Models\User|null $company
 * @property-read \App\Models\Driver|null $driver
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereBarnamehFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereHavaleFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereWeight($value)
 * @mixin \Eloquent
 */
class Partition extends Model
{
    protected $fillable = [
        'cargo_id',
        'company_id',
        'driver_id',
        'weight',
        'fare',
        'commission',
        'status',
        'havaleFile',
        'barnamehFile',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
