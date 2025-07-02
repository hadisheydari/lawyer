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
        Schema::create('companies', function (Blueprint $table) {
            //اطلاعات شرکت حمل
            $table->id();
            $table->tinyInteger('companyType')->default(0)->comment(' مقیاس شرکت حمل : 1-معمولی ، 2-بزرگ مقیاس ');
            $table->foreignId('city_id')->nullable()->comment(' شناسه شهر ')->constrained()->cascadeOnDelete();
            $table->string('registrationId')->nullable()->comment(' شناسه ثبت ');
            $table->string('nationalId')->nullable()->comment(' شناسه ملی ');
            $table->string('rahdariCode')->nullable()->comment(' کد راهداری ');
            $table->string('agentName')->nullable()->comment(' نام نماینده ');
            $table->string('agentNationalCode')->nullable()->comment(' کدملی نماینده ');
            $table->string('agentPhoneNumber')->nullable()->comment(' شماره نماینده ');
            $table->string('managerName')->nullable()->comment(' نام مدیرعامل ');
            $table->string('managerNationalCode')->nullable()->comment(' کدملی مدیرعامل ');
            $table->string('managerPhoneNumber')->nullable()->comment(' شماره مدیرعامل ');
            $table->string('address')->nullable()->comment(' آدرس ');
            $table->string('document')->nullable()->comment(' مدارک ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
