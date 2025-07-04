<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;


    protected $fillable = [
        'code',
        'name',
        'state_id',
        'latitude',
        'longitude',
    ];


    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
