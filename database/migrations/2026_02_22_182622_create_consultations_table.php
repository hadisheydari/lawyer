<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * مشاوره‌ها — هسته اصلی مشتری ساده
 *
 * type:
 *   chat        → چت متنی
 *   call        → تماس تلفنی
 *   appointment → جلسه حضوری
 *
 * status:
 *   pending     → در انتظار تأیید وکیل
 *   confirmed   → تأیید شده
 *   in_progress → در حال انجام
 *   completed   → تمام شده
 *   cancelled   → لغو شده
 *   rejected    → رد شده توسط وکیل
 *
 * نکته: payment_id با NULL شروع می‌شه و بعد از پرداخت پر می‌شه.
 * FK به payments بعد از ساخت اون جدول اضافه می‌شه (migration بعدی).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');

            $table->enum('type', ['chat', 'call', 'appointment']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 0);

            $table->unsignedSmallInteger('duration_minutes')->nullable();   // برای تماس تلفنی
            $table->dateTime('scheduled_at')->nullable();                    // برای جلسه حضوری

            $table->enum('status', [
                'pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'rejected',
            ])->default('pending');

            $table->text('lawyer_notes')->nullable();                        // یادداشت خصوصی وکیل
            $table->text('cancellation_reason')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // FK به payments بعداً اضافه می‌شه
            $table->unsignedBigInteger('payment_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['lawyer_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
