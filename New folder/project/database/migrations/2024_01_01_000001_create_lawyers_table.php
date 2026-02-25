<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('bar_number')->nullable();
            $table->string('bar_association')->nullable();
            $table->text('bio');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->unsignedInteger('cases_count')->default(0);
            $table->unsignedTinyInteger('satisfaction_percent')->default(95);
            $table->json('specialties');           // ["حقوق خانواده","دعاوی تجاری"]
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
