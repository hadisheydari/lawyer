<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'نه',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
