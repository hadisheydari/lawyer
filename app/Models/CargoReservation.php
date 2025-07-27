<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $company_id
 * @property int $cargo_id
 * @property string $status وضعیت رزرو
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo $cargo
 * @property-read \App\Models\User $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CargoReservation extends Model
{
    protected $fillable = [
        'cargo_id',
        'company_id',
        'status',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
