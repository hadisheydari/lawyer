<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lawyer;
use App\Models\LawyerSchedule;

class LawyerSchedulesSeeder extends Seeder
{
    public function run()
    {
        $lawyers = Lawyer::all();

        // ساعت کاری شنبه تا چهارشنبه: 9-12 و 14-18
        $workingDays = [
            ['day' => 0, 'start' => '09:00', 'end' => '12:00'], // شنبه صبح
            ['day' => 0, 'start' => '14:00', 'end' => '18:00'], // شنبه عصر
            ['day' => 1, 'start' => '09:00', 'end' => '12:00'], // یکشنبه صبح
            ['day' => 1, 'start' => '14:00', 'end' => '18:00'], // یکشنبه عصر
            ['day' => 2, 'start' => '09:00', 'end' => '12:00'], // دوشنبه صبح
            ['day' => 2, 'start' => '14:00', 'end' => '18:00'], // دوشنبه عصر
            ['day' => 3, 'start' => '09:00', 'end' => '12:00'], // سه‌شنبه صبح
            ['day' => 3, 'start' => '14:00', 'end' => '18:00'], // سه‌شنبه عصر
            ['day' => 4, 'start' => '09:00', 'end' => '12:00'], // چهارشنبه صبح
            ['day' => 4, 'start' => '14:00', 'end' => '18:00'], // چهارشنبه عصر
        ];

        foreach ($lawyers as $lawyer) {
            foreach ($workingDays as $schedule) {
                LawyerSchedule::create([
                    'lawyer_id' => $lawyer->id,
                    'day_of_week' => $schedule['day'],
                    'start_time' => $schedule['start'],
                    'end_time' => $schedule['end'],
                    'is_available' => true,
                ]);
            }
        }
    }
}
