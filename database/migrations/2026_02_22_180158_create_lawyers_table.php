<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول وکلا — اول ساخته می‌شه چون users به lawyers FK دارد
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('license_number')->unique();
            $table->enum('license_grade', ['1', '2', '3'])->default('1');
            $table->string('email')->unique();
            $table->string('phone', 15);
            $table->string('image')->nullable();
            $table->text('bio');
            $table->string('education')->nullable();
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->json('specializations')->nullable();

            $table->boolean('available_for_chat')->default(true);
            $table->boolean('available_for_call')->default(true);
            $table->boolean('available_for_appointment')->default(true);

            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order')->default(0);               // ترتیب نمایش

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
