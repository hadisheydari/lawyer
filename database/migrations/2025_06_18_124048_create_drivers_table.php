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
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('fatherName')->nullable();
            $table->string('nationalCode')->nullable();
            $table->string('licenseNumber')->nullable();
            $table->date('birthDate')->nullable();
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->tinyInteger('property')->default(0)->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nationalCardFile')->nullable();
            $table->string('smartCardFile')->nullable();
            $table->string('certificateFile')->nullable();
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
