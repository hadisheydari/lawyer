<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'license_number',
        'license_grade',
        'email',
        'phone',
        'image',
        'bio',
        'education',
        'experience_years',
        'specializations',
        'languages',
        'is_active',
        'available_for_chat',
        'available_for_call',
        'available_for_appointment',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'languages' => 'array',
            'is_active' => 'boolean',
            'available_for_chat' => 'boolean',
            'available_for_call' => 'boolean',
            'available_for_appointment' => 'boolean',
        ];
    }

    // Relations
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function conversations()
    {
        return $this->hasMany(ChatConversation::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableForChat($query)
    {
        return $query->where('available_for_chat', true);
    }

    public function scopeAvailableForCall($query)
    {
        return $query->where('available_for_call', true);
    }

    public function scopeAvailableForAppointment($query)
    {
        return $query->where('available_for_appointment', true);
    }

    // Helpers
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-lawyer.jpg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
