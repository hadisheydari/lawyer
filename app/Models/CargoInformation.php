<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'date_to'
    ];
    protected $casts =[
        'type' => 'string',
        'lat' => 'decimal',
        'lng' => 'decimal',
        'date_at' => 'timestamp',
        'date_to' => 'timestamp'
    ];
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class , 'cargo_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class , 'city_id');
    }
}
