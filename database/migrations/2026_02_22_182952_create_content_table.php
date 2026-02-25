<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * محتوا: مقالات، کامنت‌ها، ری‌اکشن‌ها و پیام‌های تماس
 *
 * نکته مهم: author_id در articles به lawyers FK داره، نه users
 * چون فقط وکلا مجاز به نوشتن مقاله هستند.
 *
 * approved_by در article_comments هم به lawyers FK داره.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── مقالات ─────────────────────────────────────────────────────────
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')
                ->constrained()
                ->onDelete('cascade');                                       // نویسنده = وکیل
            $table->foreignId('service_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('tags')->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedSmallInteger('reading_time')->nullable();       // دقیقه

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index('lawyer_id');
        });

        // ─── کامنت‌های مقالات ───────────────────────────────────────────────
        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('article_comments')
                ->onDelete('cascade');

            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('lawyers')                                     // وکیل تأیید می‌کنه
                ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['article_id', 'status']);
        });

        // ─── ری‌اکشن‌های مقالات ─────────────────────────────────────────────
        Schema::create('article_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'dislike', 'helpful', 'insightful'])->default('like');
            $table->timestamps();

            $table->unique(['article_id', 'user_id']);
        });

        // ─── پیام‌های تماس با ما ────────────────────────────────────────────
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('subject');
            $table->text('message');

            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->text('admin_reply')->nullable();

            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')
                ->nullable()
                ->constrained('lawyers')                                     // وکیل پاسخ می‌ده
                ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('article_reactions');
        Schema::dropIfExists('article_comments');
        Schema::dropIfExists('articles');
    }
};
