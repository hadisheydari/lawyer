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
        Schema::create('vehicle_types', function (Blueprint $table) {
            // نوع وسیله نقلیه
            $table->id();
            $table->string('brand')->nullable()->comment(' برند وسیله ');
            $table->string('name')->nullable()->comment(' مدل وسیله ');
            $table->integer('year')->nullable()->comment(' سال تولید وسیله ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
