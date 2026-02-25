<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatConversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'lawyer_id',
        'consultation_id',
        'case_id',
        'title',
        'status',
        'last_message_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'closed_at'       => 'datetime',
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')->oldest();
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latest();
    }


    public function isForSimpleClient(): bool
    {
        return $this->consultation_id !== null;
    }

    public function isForSpecialClient(): bool
    {
        return $this->case_id !== null;
    }

    public function getUnreadCountFor(string $type, int $id): int
    {
        return $this->messages()
            ->where('sender_type', '!=', $type)
            ->where('is_read', false)
            ->count();
    }

    public function markReadFor(string $type, int $id): void
    {
        $this->messages()
            ->where('sender_type', '!=', $type)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
