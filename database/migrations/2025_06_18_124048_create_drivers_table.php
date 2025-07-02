<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            //اطلاعات راننده
            $table->id();
            $table->foreignId('user_id')->nullable()->comment(' شناسه کاربر  ')->constrained()->cascadeOnDelete();
            $table->string('fatherName')->nullable()->comment(' نام پدر ');
            $table->string('nationalCode')->nullable()->comment(' کدملی ');
            $table->string('certificateNumber')->nullable()->comment(' شماره گواهینامه ');
            $table->date('birthDate')->nullable()->comment(' تاریخ تولد ');
            $table->foreignId('city_id')->nullable()->comment(' شناسه شهر ')->constrained()->cascadeOnDelete();
            $table->tinyInteger('property')->default(0)->comment(' وضعیت ملکی : 1-ملکی ، 2-غیرملکی ');
            $table->foreignId('company_id')->nullable()->comment(' شناسه شرکن حمل ')->constrained()->cascadeOnDelete();
            $table->string('nationalCardFile')->nullable()->comment(' فایل کارت ملی ');
            $table->string('smartCardFile')->nullable()->comment(' فایل کارت هوشمند  ');
            $table->string('certificateFile')->nullable()->comment(' فایل کواهینامه ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
