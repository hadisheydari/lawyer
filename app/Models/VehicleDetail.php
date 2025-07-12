<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleDetail extends Model
{
    protected $fillable = [
        'brand',
        'name',
        'motorCode',
        'bodyCode',
        'year'
    ];

    public function vehicles():HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
