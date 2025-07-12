<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'name',
        'code',
        'province_id',
        'latitude',
        'longitude'
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
