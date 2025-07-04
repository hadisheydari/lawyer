<?php

use App\Enums\PlateLatter;
use App\Models\TrailerDetail;
use App\Models\VehicleDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->id();
            $table->string('brand')->comment('برند');
            $table->string('name')->comment('مدل');
            $table->string('motorCode')->comment('شناسه موتور');
            $table->string('bodyCode')->comment('شناسه بدنه');
            $table->integer('year')->comment('سال ساخت');
            $table->timestamps();
        });


        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('smart_number')->index()->comment('شماره هوشمند');
            $table->unsignedBigInteger('cost_center')->index()->comment('مرکز هزینه');
            $table->unsignedTinyInteger('plate_first')->comment('قسمت اول پلاک');
            $table->unsignedSmallInteger('plate_second')->comment('قسمت دوم پلاک');
            $table->unsignedTinyInteger('plate_third')->comment('قسمت سوم پلاک(کد استان)');
            $table->enum('plate_letter', PlateLatter::LETTERS)->comment('حرف پلاک');
            $table->enum('plate_type', ['general', 'governmental', 'personal'])->default('personal')->comment(' نوع پلاک ');
            $table->enum('status', ['active', 'not_active'])->default('active');
            $table->enum('type', ['vehicle', 'trailer'])->default('vehicle')->comment('نوع خودرو');
            $table->foreignIdFor(VehicleDetail::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_details');
        Schema::dropIfExists('vehicles');
    }
};
