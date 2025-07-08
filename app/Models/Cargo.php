<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $fillable = [
        'owner_id',
        'type',
        'weight',
        'number',
        'thickness',
        'length',
        'width',
        'insurance',
        'fare',
        'fare_type',
        'cargo_type_id',
        'packing_id',
        'description'
    ];

    protected function casts(): array
    {
        return [
            'insurance' => 'integer',
            'fare' => 'integer',
            'weight' => 'integer',
            'number' => 'integer',
            'thickness' => 'integer',
            'length' => 'integer',
            'width' => 'integer',
            'type' => 'string',
            'fare_type' => 'string',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class);
    }

    public function packing(): BelongsTo
    {
        return $this->belongsTo(Packing::class);
    }

    public function cargoInformation(): HasMany
    {
        return $this->hasMany(CargoInformation::class);
    }

    public function cargoReservations(): HasMany
    {
        return $this->hasMany(CargoReservation::class);
    }

    public function cargoBids(): HasMany
    {
        return $this->hasMany(CargoBid::class);
    }

    public function partitions(): HasMany
    {
        return $this->hasMany(Partition::class);
    }
}
