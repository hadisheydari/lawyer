<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول کاربران — بعد از lawyers ساخته می‌شه (FK به lawyers)
 *
 * user_type:
 *   simple  → مشتری ساده (مشاوره یکبار، پرداخت per-session)
 *   special → موکل ویژه (قرارداد وکالت، پرونده، اقساط)
 *
 * status:
 *   active    → فعال
 *   pending   → در انتظار تأیید
 *   blocked   → مسدود
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 15)->unique();
            $table->string('email')->nullable()->unique();
            $table->string('national_code', 10)->nullable()->unique();
            $table->string('password')->nullable();         // null برای OTP-only

            $table->enum('user_type', ['simple', 'special'])->default('simple');
            $table->enum('status', ['active', 'pending', 'blocked'])->default('active');

            // ارتقا به موکل ویژه — توسط کدام وکیل؟
            $table->foreignId('upgraded_by')
                ->nullable()
                ->constrained('lawyers')
                ->nullOnDelete();
            $table->timestamp('upgraded_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_type', 'status']);
        });

        // کد OTP برای احراز هویت با موبایل
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15)->index();
            $table->string('code', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->index(['phone', 'is_used']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('otp_codes');
        Schema::dropIfExists('users');
    }
};
