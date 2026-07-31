<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // پاک‌سازی جداول نصفه‌کاره قبلی در صورت وجود
        Schema::dropIfExists('article_seo');
        Schema::dropIfExists('article_metrics');
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');

        // بازگرداندن/تکمیل ستون‌های لازم روی articles
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'tags')) {
                $table->json('tags')->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('articles', 'view_count')) {
                $table->unsignedInteger('view_count')->default(0)->after('published_at');
            }
            if (!Schema::hasColumn('articles', 'reading_time')) {
                $table->unsignedSmallInteger('reading_time')->nullable()->after('view_count');
            }
            if (!Schema::hasColumn('articles', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('articles', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (Schema::hasColumn('articles', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('articles', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
        });

        // دسته‌بندی‌ها — هر دسته یک صفحه اختصاصی برای سئو دارد
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->unique(['article_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['tags', 'view_count', 'reading_time', 'meta_title', 'meta_description']);
        });
    }
};