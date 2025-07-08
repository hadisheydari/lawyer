<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'type',
        'vehicle_detail_id',
    ];

    public function vehicleDetail(): BelongsTo
    {
        return $this->belongsTo(VehicleDetail::class);
    }
}
