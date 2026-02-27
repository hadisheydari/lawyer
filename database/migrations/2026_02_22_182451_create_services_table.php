<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();             // کلاس Font Awesome
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();           // ویژگی‌های خدمت
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order')->default(0);
            $table->decimal('base_price', 15, 0)->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
