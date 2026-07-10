<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // ۱. ساخت جداول جدید
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('article_seo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('article_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedSmallInteger('reading_time')->nullable();
            $table->timestamps();
        });

        // ۲. انتقال داده‌ها از جدول قدیم به جداول جدید
        // استفاده از chunk برای جلوگیری از پر شدن حافظه (RAM) در صورت زیاد بودن مقالات
        DB::table('articles')->orderBy('id')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                // انتقال سئو
                DB::table('article_seo')->insert([
                    'article_id' => $article->id,
                    'meta_title' => $article->meta_title,
                    'meta_description' => $article->meta_description,
                    'meta_keywords' => $article->meta_keywords,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // انتقال آمار
                DB::table('article_metrics')->insert([
                    'article_id' => $article->id,
                    'view_count' => $article->view_count,
                    'reading_time' => $article->reading_time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // انتقال و ساخت دسته‌بندی
                if (!empty($article->category)) {
                    $categorySlug = Str::slug($article->category);
                    
                    // بررسی وجود دسته‌بندی یا ایجاد آن برای جلوگیری از ثبت تکراری
                    $category = DB::table('categories')->where('slug', $categorySlug)->first();
                    
                    if (!$category) {
                        $categoryId = DB::table('categories')->insertGetId([
                            'name' => $article->category,
                            'slug' => $categorySlug . '-' . uniqid(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $categoryId = $category->id;
                    }

                    // اتصال مقاله به دسته‌بندی در جدول Pivot
                    DB::table('article_category')->insert([
                        'article_id' => $article->id,
                        'category_id' => $categoryId,
                    ]);
                }
            }
        });

        // ۳. حذف ستون‌های اضافی از جدول مقالات
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'tags',
                'view_count',
                'reading_time',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);
        });
    }

    public function down()
    {
        // در صورت نیاز به رول‌بک (Rollback)، ستون‌ها دوباره اضافه می‌شوند
        Schema::table('articles', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->longText('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->smallInteger('reading_time')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
        });

        Schema::dropIfExists('article_metrics');
        Schema::dropIfExists('article_seo');
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');
    }
};