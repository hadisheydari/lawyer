<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use mysql_xdevapi\Table;

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
        'description',
    ];

    protected $casts = [
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

    public function productOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class, 'cargo_type_id');
    }

    public function packing(): BelongsTo
    {
        return $this->belongsTo(Packing::class, 'packing_id');
    }
}
