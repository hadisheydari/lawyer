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

    protected function casts(): array
    {
        return [
            'offered_fare' => 'integer'
        ];
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class , 'cargo_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class , 'company_id');
    }
}
