<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Lawyer;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fa_IR');

        // گرفتن آیدی وکلا و خدمات موجود برای ایجاد رابطه
        $lawyerIds = Lawyer::pluck('id')->toArray();
        $serviceIds = Service::pluck('id')->toArray();

        // دسته‌بندی‌های فرضی برای مقالات حقوقی
        $categories = ['حقوق خانواده', 'دعاوی ملکی', 'حقوق کیفری', 'امور تجاری', 'ارث و وصیت'];

        for ($i = 1; $i <= 15; $i++) {
            $title = $faker->realText(50);
            
            Article::create([
                'lawyer_id'        => $faker->randomElement($lawyerIds),
                'service_id'       => $faker->randomElement($serviceIds),
                'title'            => $title,
                'slug'             => Str::slug($title, '-') . '-' . rand(100, 999),
                'excerpt'          => $faker->realText(150),
                'content'          => $faker->realText(1000),
                'featured_image'   => null, // یا آدرس یک تصویر رندوم
                'category'         => $faker->randomElement($categories),
                'tags'             => [$faker->word, $faker->word, $faker->word],
                'status'           => 'published',
                'published_at'     => now()->subDays(rand(1, 30)),
                'view_count'       => rand(50, 5000),
                'reading_time'     => rand(3, 15),
                'meta_title'       => $title,
                'meta_description' => $faker->realText(100),
                'meta_keywords'    => [$faker->word, $faker->word],
            ]);
        }
    }
}