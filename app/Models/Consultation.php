<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'lawyer_id',
        'service_id',
        'type',
        'title',
        'description',
        'price',
        'duration_minutes',
        'scheduled_at',
        'status',
        'lawyer_notes',
        'cancellation_reason',
        'confirmed_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'payment_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'price' => 'decimal:0',
    ];

    /*
    |--------------------------------
    | Relationships
    |--------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
