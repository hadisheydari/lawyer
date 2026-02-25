<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactRequest extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'service', 'message', 'status', 'ip',
    ];

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
