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
        Schema::table('articles', function (Blueprint $t) {
            $t->string('featured_image_alt')->nullable()->after('featured_image');
            $t->timestamp('content_updated_at')->nullable()->after('published_at');
            $t->json('faqs')->nullable();
            $t->json('related_article_ids')->nullable();
        });
        Schema::create('article_redirects', function (Blueprint $t) {
            $t->id();
            $t->string('old_slug')->unique();
            $t->foreignId('article_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_redirects');
    }
};
