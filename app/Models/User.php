<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'national_code',
        'password',
        'user_type',
        'status',
        'upgraded_by',
        'upgraded_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'     => 'hashed',
            'upgraded_at'  => 'datetime',
        ];
    }


    public function upgradedByLawyer()
    {
        return $this->belongsTo(Lawyer::class, 'upgraded_by');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function cases()
    {
        return $this->hasMany(LegalCase::class);
    }

    public function conversations()
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function reactions()
    {
        return $this->hasMany(ArticleReaction::class);
    }


    public function isSimple(): bool
    {
        return $this->user_type === 'simple';
    }

    public function isSpecial(): bool
    {
        return $this->user_type === 'special';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }


    public function scopeSimple($query)
    {
        return $query->where('user_type', 'simple');
    }

    public function scopeSpecial($query)
    {
        return $query->where('user_type', 'special');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
