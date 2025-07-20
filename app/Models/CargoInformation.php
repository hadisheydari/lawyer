<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $cargo_id
 * @property string|null $type نوع (مبدا/مقصد)
 * @property float|null $lat عرض جغرافیایی
 * @property float|null $lng طول جغرافیایی
 * @property int|null $city_id
 * @property string|null $description توضیحات
 * @property string|null $address آدرس دقیق
 * @property int|null $date_at تاریخ و ساعت شروع
 * @property int|null $date_to تاریخ و ساعت پایان
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo $cargo
 * @property-read \App\Models\City|null $city
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CargoInformation extends Model
{
    protected $fillable = [
        'cargo_id',
        'type',
        'lat',
        'lng',
        'city_id',
        'description',
        'address',
        'date_at',
        'date_to',
    ];

    protected $casts = [
        'type' => 'string',
        'lat' => 'float',
        'lng' => 'float',
        'date_at' => 'timestamp',
        'date_to' => 'timestamp',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
