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
        Schema::create('insurance_companies', function (Blueprint $table) {
            //بیمه
            $table->id();
            $table->string('name')->nullable()->comment(' نام بیمه  ');
            $table->mediumInteger('code')->nullable()->comment(' کد بیمه  ');
            $table->decimal('coefficient')->nullable()->comment(' ضریب بیمه  ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_companies');
    }
};
