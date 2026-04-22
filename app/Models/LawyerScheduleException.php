<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerScheduleException extends Model
{
    protected $fillable = [
        'lawyer_id',
        'exception_date',
        'is_available',
        'reason'
    ];

    protected $casts = [
        'exception_date' => 'date',
        'is_available' => 'boolean',
    ];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }
}
