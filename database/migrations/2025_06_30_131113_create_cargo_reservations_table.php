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
        Schema::create('cargo_reservations', function (Blueprint $table) {
            // رزرو بار برای شرکت حمل
            $table->id();
            $table->foreignId('cargo_id')->comment('شناسه ی بار ')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->comment('شناسه ی شرکت حمل  ')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('وضعیت تایید و یا رد بار ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_reservations');
    }
};
