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
        Schema::create('partitions', function (Blueprint $table) {
            // پارتیشن ها
            $table->id();
            $table->foreignId('cargo_id')->nullable()->comment('شناسه ی بار ')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('شناسه ی شرکت حمل  ')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->comment('شناسه ی راننده  ')->constrained('drivers')->cascadeOnDelete();
            $table->unsignedInteger('weight')->nullable()->comment(' وزن بار بر اساس تن ');
            $table->unsignedBigInteger('fare')->nullable()->comment(' قیمت کرایه ');
            $table->unsignedBigInteger('commission')->nullable()->comment(' کمیسیو ');
            $table->enum('status', ['free', 'reserved', 'havale', 'barnameh', 'delivered'])->default('free')->comment(' وضعیت حمل بار  ');
            $table->string('havaleFile')->nullable()->comment(' فایل حواله   ');
            $table->string('barnamehFile')->nullable()->comment(' فایل بارنامه   ');
            $table->timestamps();

            $table->index('cargo_id');
            $table->index('company_id');
            $table->index('driver_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partitions');
    }
};
