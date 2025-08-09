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

    /**
     * رابطه با Cargo
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * رابطه با شرکت (کاربر)
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Scope: فیلتر بر اساس نوع cargo یا company_id
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $cargo
     *      - 'rfq' => فقط رکوردهایی که cargo.type = rfq
     *      - 'reserve' => فقط رکوردهایی که cargo.type = reserve
     *      - عدد => فقط رکوردهایی که company_id = عدد
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByCargo($query, $cargo)
    {
        if (in_array($cargo, ['rfq', 'reserve'])) {
            return $query->whereHas('cargo', function ($q) use ($cargo) {
                $q->where('type', $cargo);
            });
        }

        if (is_numeric($cargo)) {
            return $query->where('company_id', $cargo);
        }

        return $query;
    }
}
