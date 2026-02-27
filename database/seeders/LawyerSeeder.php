<?php

namespace Database\Seeders;

use App\Models\Lawyer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LawyerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fa_IR');

        // ۱. ایجاد دو وکیل اصلی برای هماهنگی با ویوهای طراحی شده
        $mainLawyers = [
            [
                'name' => 'بابک ابدالی',
                'slug' => 'babak-abdali',
                'license_number' => '12345/ک',
                'license_grade' => '1',
                'email' => 'abdali@example.com',
                'phone' => '09131146888',
                'bio' => 'بابک ابدالی، وکیل پایه یک دادگستری با بیش از ۲۸ سال سابقه فعالیت در مراجع قضایی استان اصفهان، از برجسته‌ترین وکلای متخصص در حوزه‌های تجاری، ملکی و کیفری است.',
                'education' => 'کارشناسی ارشد حقوق خصوصی',
                'experience_years' => 28,
                'specializations' => ['دعاوی تجاری', 'دعاوی ملکی', 'حقوق کیفری'],
                'order' => 1,
            ],
            [
                'name' => 'زهرا جوشقانی',
                'slug' => 'zahra-jooshghani',
                'license_number' => '67890/ک',
                'license_grade' => '1',
                'email' => 'jooshghani@example.com',
                'phone' => '09132888859',
                'bio' => 'زهرا جوشقانی، وکیل پایه یک دادگستری با ۲۰ سال تخصص در حوزه حقوق خانواده، ارث و ترکه، یکی از معتمدترین وکلای این حوزه در استان اصفهان است.',
                'education' => 'کارشناسی ارشد حقوق خانواده',
                'experience_years' => 20,
                'specializations' => ['حقوق خانواده', 'ارث و وصیت', 'طلاق'],
                'order' => 2,
            ]
        ];

        foreach ($mainLawyers as $data) {
            Lawyer::create(array_merge($data, [
                'available_for_chat' => true,
                'available_for_call' => true,
                'available_for_appointment' => true,
                'is_active' => true,
            ]));
        }

        // ۲. ایجاد ۳ وکیل تصادفی دیگر برای تست لیست‌ها
        $specializations = ['حقوق ثبتی', 'دعاوی مالیاتی', 'حقوق کار', 'جرایم رایانه‌ای', 'حقوق تصادفات'];

        for ($i = 0; $i < 3; $i++) {
            $name = $faker->name;
            Lawyer::create([
                'name' => $name,
                'slug' => Str::slug($name, '-'),
                'license_number' => rand(10000, 99999) . '/ک',
                'license_grade' => (string)rand(1, 3),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'bio' => $faker->realText(300),
                'education' => 'کارشناسی ارشد حقوق',
                'experience_years' => rand(5, 15),
                'specializations' => $faker->randomElements($specializations, 2),
                'available_for_chat' => (bool)rand(0, 1),
                'available_for_call' => (bool)rand(0, 1),
                'available_for_appointment' => true,
                'is_active' => true,
                'order' => $i + 3,
            ]);
        }
    }
}