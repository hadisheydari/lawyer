<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');                       // دعاوی ملکی، خانواده ...
            $table->text('excerpt')->nullable();              // چکیده برای لیست
            $table->longText('body');                         // متن کامل مقاله
            $table->string('cover_image')->nullable();
            $table->foreignId('lawyer_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('read_minutes')->default(5);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
