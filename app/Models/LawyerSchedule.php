<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LawyerSchedule extends Model
{
    protected $fillable = [
        'lawyer_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * چک می‌کنه این ساعت در یک تاریخ خاص رزرو شده یا نه
     */
    public function isBookedOn($date, $startTime)
    {
        return Consultation::where('lawyer_id', $this->lawyer_id)
            ->whereDate('scheduled_at', $date)
            ->whereTime('scheduled_at', $startTime)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    /**
     * تولید لیست ساعت‌های موجود بین start و end (هر ساعت یک slot)
     */
    public function generateTimeSlots()
    {
        $slots = [];
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        while ($start->lt($end)) {
            $slotEnd = $start->copy()->addHour();
            if ($slotEnd->lte($end)) {
                $slots[] = [
                    'start_time' => $start->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                ];
            }
            $start->addHour();
        }

        return $slots;
    }
}
