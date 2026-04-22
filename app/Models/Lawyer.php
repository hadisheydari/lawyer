<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Lawyer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'available_for_chat',
        'available_for_call',
        'available_for_appointment',
        'is_active',
        'order',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'is_active' => 'boolean',
            'available_for_chat' => 'boolean',
            'available_for_call' => 'boolean',
            'available_for_appointment' => 'boolean',
        ];
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    public function upgradedClients()
    {
        return $this->hasMany(User::class, 'upgraded_by');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function cases()
    {
        return $this->hasMany(LegalCase::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function conversations()
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function schedules()
    {
        return $this->hasMany(LawyerSchedule::class);
    }

    public function scheduleExceptions()
    {
        return $this->hasMany(LawyerScheduleException::class);
    }

    /**
     * دریافت ساعت‌های آزاد یک روز خاص
     */
    public function getAvailableSlots($date)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        $dayOfWeek = ($carbonDate->dayOfWeek + 1) % 7; // تبدیل به 0=شنبه

        // چک استثنا
        $exception = $this->scheduleExceptions()
            ->where('exception_date', $date)
            ->first();

        if ($exception && ! $exception->is_available) {
            return []; // روز تعطیل
        }

        // دریافت برنامه روزانه
        $schedule = $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (! $schedule) {
            return [];
        }

        // تولید ساعت‌ها
        $allSlots = $schedule->generateTimeSlots();

        // فیلتر رزرو شده‌ها
        return collect($allSlots)->filter(function ($slot) use ($date, $schedule) {
            return ! $schedule->isBookedOn($date, $slot['start_time']);
        })->values()->all();
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : asset('images/default-lawyer.jpg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
