<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleDetail;
use App\Enums\Vehicle\PlateLatter;
use App\Enums\Vehicle\PlateType;
use App\Enums\Vehicle\Status;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $detail = VehicleDetail::create([
                'brand'      => fake()->randomElement(['Hyundai', 'Kia', 'Peugeot', 'Toyota', 'Nissan']),
                'name'       => fake()->word(),
                'motorCode'  => 'MTR-' . fake()->unique()->numberBetween(10000, 99999),
                'bodyCode'   => 'BDY-' . fake()->unique()->numberBetween(10000, 99999),
                'year'       => fake()->numberBetween(2000, 2024),
            ]);

            Vehicle::create([
                'smart_number'     => fake()->unique()->numberBetween(100000000, 999999999),
                'cost_center'      => fake()->numberBetween(1000, 9999),
                'plate_first'      => fake()->numberBetween(10, 99),
                'plate_second'     => fake()->numberBetween(100, 999),
                'plate_third'      => fake()->numberBetween(1, 99),
                'plate_letter'     => fake()->randomElement(PlateLatter::LETTERS),
                'plate_type'       => fake()->randomElement(PlateType::TYPES),
                'status'           => fake()->randomElement(Status::STATUSES),
                'vehicle_detail_id'=> $detail->id,
                'driver_id'        => null, // بدون راننده
            ]);
        }
    }
}
