<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Packing;
use Faker\Factory as Faker;


class PackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packings = [
            'کارتن',
            'پالت چوبی',
            'پالت پلاستیکی',
            'بشکه',
            'جعبه فلزی',
            'جعبه چوبی',
            'کیسه',
            'کیسه جامبو',
            'کیسه پارچه‌ای',
            'نایلون',
            'تانکر',
            'سطل پلاستیکی',
            'بشکه پلاستیکی',
            'قوطی فلزی',
            'کارتن شیرینگ‌شده',
            'بسته وکیوم‌شده',
            'پاکت کاغذی',
            'رول پیچ‌شده',
            'با روکش نایلون حباب‌دار',
            'کفی پالت‌بندی‌شده',
        ];

        foreach ($packings as $name) {
            Packing::create(['name' => $name]);
        }
    }
}
