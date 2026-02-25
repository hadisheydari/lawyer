<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ترتیب مهم است — اول lawyers چون بقیه FK دارند
        $this->call([
            LawyerSeeder::class,
            ServiceSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
