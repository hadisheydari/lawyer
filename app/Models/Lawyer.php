<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Lawyer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name', 'slug', 'license_number', 'license_grade',
        'email', 'phone', 'image', 'bio', 'education',
        'experience_years', 'specializations',
        'available_for_chat', 'available_for_call', 'available_for_appointment',
        'is_active', 'order',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'specializations'           => 'array',
            'is_active'                 => 'boolean',
            'available_for_chat'        => 'boolean',
            'available_for_call'        => 'boolean',
            'available_for_appointment' => 'boolean',
        ];
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    public function upgradedClients()   { return $this->hasMany(User::class, 'upgraded_by'); }
    public function consultations()     { return $this->hasMany(Consultation::class); }
    public function cases()             { return $this->hasMany(LegalCase::class); }
    public function articles()          { return $this->hasMany(Article::class); }
    public function conversations()     { return $this->hasMany(ChatConversation::class); }
    public function schedules()         { return $this->hasMany(LawyerSchedule::class); }
    public function scheduleExceptions(){ return $this->hasMany(LawyerScheduleException::class); }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-lawyer.jpg');
    }

    public function getRouteKeyName(): string { return 'slug'; }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * محاسبه اسلات‌های آزاد برای یک روز مشخص
     * ساعت کاری پیش‌فرض: ۱۷:۰۰ تا ۲۱:۰۰ هر ۳۰ دقیقه یکبار
     */
    public function getAvailableSlots(string $date): array
    {
        $carbonDate = Carbon::parse($date);

        // جمعه تعطیل است
        if ($carbonDate->dayOfWeek === Carbon::FRIDAY) {
            return [];
        }

        // بررسی استثناهای تقویم (روزهای تعطیل یا خاص)
        $exception = $this->scheduleExceptions()
            ->where('exception_date', $date)
            ->first();

        if ($exception && !$exception->is_available) {
            return [];
        }

        // ساعت کاری پیش‌فرض — در آینده می‌توان از LawyerSchedule خواند
        $workStart = '17:00';
        $workEnd   = '21:00';

        // اگر جدول schedules داده داشت، از آن بخوان
        $dayOfWeek = ($carbonDate->dayOfWeek + 1) % 7; // تبدیل به فرمت شمسی
        $schedule = $this->schedules()->where('day_of_week', $dayOfWeek)->first();

        if ($schedule && $schedule->is_available) {
            $workStart = $schedule->start_time;
            $workEnd   = $schedule->end_time;
        } elseif ($schedule && !$schedule->is_available) {
            return [];
        }

        // پیدا کردن نوبت‌های از قبل گرفته‌شده
        $bookedTimes = Consultation::where('lawyer_id', $this->id)
            ->whereDate('scheduled_at', $date)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->pluck('scheduled_at')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();

        // ساخت اسلات‌های ۳۰ دقیقه‌ای
        $slots = [];
        $current = Carbon::parse("{$date} {$workStart}");
        $end     = Carbon::parse("{$date} {$workEnd}");

        while ($current->lt($end)) {
            $time = $current->format('H:i');
            if (!in_array($time, $bookedTimes)) {
                $slots[] = [
                    'start_time' => $time,
                    'end_time'   => $current->copy()->addMinutes(30)->format('H:i'),
                ];
            }
            $current->addMinutes(30);
        }

        return $slots;
    }
}