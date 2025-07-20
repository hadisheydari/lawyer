<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name نوع بار
 * @property int $code کد بار
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cargo> $cargos
 * @property-read int|null $cargos_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CargoType extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class, 'cargo_type_id');
    }
}
