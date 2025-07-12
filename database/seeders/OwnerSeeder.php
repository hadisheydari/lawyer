<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'مدیر سیستم',
                'phone' => '09123456789',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'verified_at' => now(),
            ]
        );
    }
}
