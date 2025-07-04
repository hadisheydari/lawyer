<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partition extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_id',
        'company_id',
        'driver_id',
        'weight',
        'fare',
        'commission',
        'status',
        'havaleFile',
        'barnamehFile',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }}
