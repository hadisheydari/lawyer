<?php

use App\Enums\Cargo\CargoStatus;
use App\Enums\Cargo\FareType;
use App\Enums\Cargo\LocationType;
use App\Enums\Cargo\ReservationStatus;
use App\Enums\Cargo\Type;
use App\Enums\Entity\PropertyType;
use App\Models\Cargo;
use App\Models\CargoType;
use App\Models\City;
use App\Models\Province;
use App\Models\Driver;
use App\Models\Packing;
use App\Models\Insurance;
use App\Models\User;
use App\Models\VehicleDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {


        Schema::create('partitions', function (Blueprint $table) {
            $table->id();
            $table->enum('property', PropertyType::TYPES)->nullable()->comment('owned -> ملکی, non_owned -> غیرملکی');
            $table->foreignIdFor(User::class, 'company_id')->nullable()->comment('شناسه ی کامپانی')->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Cargo::class)->nullable()->comment('شناسه ی بار')->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Driver::class)->nullable()->comment('راننده ای که این بار رو رزرو کرده')->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(VehicleDetail::class)->nullable()->comment('شناسه نوع بار گیری ')->index()->constrained()->nullOnDelete();
            $table->unsignedInteger('weight')->nullable()->comment('وزن (تن)');
            $table->unsignedBigInteger('fare')->nullable()->comment('کرایه');
            $table->unsignedBigInteger('commission')->nullable()->comment('کمیسیون');
            $table->enum('status', CargoStatus::STATUSES)->default(CargoStatus::FREE)->comment('وضعیت حمل');
            $table->string('havaleFile')->nullable()->comment('فایل حواله');
            $table->string('barnamehFile')->nullable()->comment('فایل بارنامه');
            $table->timestamps();
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('partitions');
    }
};
