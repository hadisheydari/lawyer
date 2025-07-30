<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleDetail;
use Faker\Factory as Faker;

class VehicleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run(): void
    {
        $faker = Faker::create('fa_IR'); // فارسی برای نام برند

        $brands = [
            'ایران‌خودرو', 'سایپا', 'بهمن', 'کرمان‌موتور', 'ماموت', 'پارس‌خودرو', 'زرین خودرو', 'آذهایتکس'
        ];

        $types = [
            'یخچالدار', 'کفی', 'لبه‌دار', 'کمپرسی', 'تانکر', 'تریلی چادری', 'تریلی کانتینردار', 'تریلی یخچالی'
        ];

        $wheels = ['۶ چرخ', '۱۰ چرخ', '۱۲ چرخ', '۱۸ چرخ'];

        for ($i = 0; $i < 100; $i++) {
            VehicleDetail::create([
                'brand' => $faker->randomElement($brands),
                'name' => $faker->randomElement($types) . ' ' . $faker->randomElement($wheels),
                'motorCode' => strtoupper($faker->bothify('MTR-####')),
                'bodyCode' => strtoupper($faker->bothify('BDY-####')),
                'year' => $faker->numberBetween(1385, 1403),
            ]);
        }
    }

}
