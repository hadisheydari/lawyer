<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CargoType extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'integer',
    ];

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class, 'cargo_type_id');
    }
}
