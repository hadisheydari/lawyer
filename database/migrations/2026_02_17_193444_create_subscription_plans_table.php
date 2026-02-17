<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['normal', 'special', 'premium'])->unique();
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // امکانات پلن
            $table->decimal('price', 10, 0)->default(0); // تومان
            $table->integer('duration_days')->default(30); // مدت اعتبار (روز)
            $table->integer('chat_limit')->nullable(); // تعداد چت
            $table->integer('call_minutes')->nullable(); // دقیقه تماس
            $table->integer('appointment_limit')->nullable(); // تعداد نوبت
            $table->boolean('direct_access')->default(false); // دسترسی مستقیم
            $table->boolean('priority_support')->default(false); // پشتیبانی اولویت‌دار
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
