<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lawyer;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fa_IR');

        $lawyerIds  = Lawyer::pluck('id')->toArray();
        $serviceIds = Service::pluck('id')->toArray();

        $categoryNames = ['حقوق خانواده', 'دعاوی ملکی', 'حقوق کیفری', 'امور تجاری', 'ارث و وصیت'];
        $categories = collect($categoryNames)->map(fn ($name) => Category::firstOrCreate(
            ['slug' => Category::makeSlug($name)],
            ['name' => $name]
        ));

        for ($i = 1; $i <= 15; $i++) {
            $title = $faker->realText(50);

            $article = Article::create([
                'lawyer_id'        => $faker->randomElement($lawyerIds),
                'service_id'       => $faker->randomElement($serviceIds),
                'title'            => $title,
                'slug'             => trim(preg_replace('/[^\p{L}\p{N}\-]+/u', '', preg_replace('/\s+/u', '-', $title)), '-') . '-' . rand(100, 999),
                'excerpt'          => $faker->realText(150),
                'content'          => $faker->realText(1000),
                'status'           => 'published',
                'published_at'     => now()->subDays(rand(1, 30)),
                'view_count'       => rand(50, 5000),
                'reading_time'     => rand(3, 15),
                'tags'             => [$faker->word, $faker->word, $faker->word],
                'meta_title'       => $title,
                'meta_description' => $faker->realText(150),
            ]);

            $article->categories()->attach($categories->random(rand(1, 2))->pluck('id'));
        }
    }
}