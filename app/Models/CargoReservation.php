<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CargoReservation extends Model
{
    protected $fillable = [
        'cargo_id',
        'company_id',
        'status',
    ];

    protected $casts = [
        'cargo_id' => 'integer',
        'company_id' => 'integer',
        'status' => 'string',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

}
