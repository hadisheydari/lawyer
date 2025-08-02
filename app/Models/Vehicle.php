<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Driver;
/**
 * @property int $id
 * @property int $smart_number شماره هوشمند
 * @property int $cost_center مرکز هزینه
 * @property int $plate_first قسمت اول پلاک
 * @property int $plate_second قسمت دوم پلاک
 * @property int $plate_third قسمت سوم پلاک(کد استان)
 * @property string $plate_letter حرف پلاک
 * @property string $plate_type نوع پلاک
 * @property string $status وضعیت
 * @property int $vehicle_detail_id
 * @property int $driver_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VehicleDetail $vehicleDetail
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCostCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateThird($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSmartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereVehicleDetailId($value)
 * @mixin \Eloquent
 */
class Vehicle extends Model
{
    protected $fillable = [
        'smart_number',
        'cost_center',
        'plate_first',
        'plate_second',
        'plate_third',
        'plate_letter',
        'plate_type',
        'status',
        'vehicle_detail_id',
        'driver_id',
    ];

    public function vehicleDetail(): BelongsTo
    {
        return $this->belongsTo(VehicleDetail::class);
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
