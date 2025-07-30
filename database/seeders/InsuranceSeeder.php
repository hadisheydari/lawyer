<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Insurance;
use Faker\Factory as Faker;
class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insurances = [
            ['name' => 'بیمه ایران', 'code' => 101, 'coefficient' => '1.00'],
            ['name' => 'بیمه آسیا', 'code' => 102, 'coefficient' => '0.95'],
            ['name' => 'بیمه البرز', 'code' => 103, 'coefficient' => '0.97'],
            ['name' => 'بیمه دانا', 'code' => 104, 'coefficient' => '1.02'],
            ['name' => 'بیمه معلم', 'code' => 105, 'coefficient' => '1.01'],
            ['name' => 'بیمه پارسیان', 'code' => 106, 'coefficient' => '0.99'],
            ['name' => 'بیمه رازی', 'code' => 107, 'coefficient' => '0.94'],
            ['name' => 'بیمه سامان', 'code' => 108, 'coefficient' => '0.96'],
            ['name' => 'بیمه دی', 'code' => 109, 'coefficient' => '1.03'],
            ['name' => 'بیمه ملت', 'code' => 110, 'coefficient' => '0.98'],
        ];

        foreach ($insurances as $insurance) {
            Insurance::create($insurance);
        }
    }
}
