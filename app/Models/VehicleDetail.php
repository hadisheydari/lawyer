<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $brand برند
 * @property string $name مدل
 * @property string $motorCode شناسه موتور
 * @property string $bodyCode شناسه بدنه
 * @property int $year سال ساخت
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicles
 * @property-read int|null $vehicles_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereBodyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereMotorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereYear($value)
 *
 * @mixin \Eloquent
 */
class VehicleDetail extends Model
{
    protected $fillable = [
        'brand',
        'name',
        'motorCode',
        'bodyCode',
        'year',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
