<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoBid extends Model
{
    protected $fillable = [
        'cargo_id',
        'company_id',
        'offered_fare',
        'note',
        'status'

    ];
    protected $casts = [
        'offered_fare' => 'integer',
        'status' => 'string',
    ];
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargos::class , 'cargo_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class , 'company_id');
    }
}
