<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packing extends Model
{
    protected $fillable = [
        'name'
    ];

    public function cargos():  HasMany
    {
        return $this->hasMany(Cargo::class);
    }
}
