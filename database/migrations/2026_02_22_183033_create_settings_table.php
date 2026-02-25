<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تنظیمات سیستم و تقویم کاری وکلا
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── تنظیمات key-value ──────────────────────────────────────────────
        // نمونه key ها:
        //   pricing.chat_price          → قیمت مشاوره چت
        //   pricing.call_price_per_min  → قیمت هر دقیقه تماس
        //   pricing.appointment_price   → قیمت جلسه حضوری
        //   calculator.mahrieh_index    → شاخص بانک مرکزی برای مهریه
        //   calculator.diyeh_amount     → مبلغ دیه سال جاری
        //   calculator.stamp_duty_pct   → درصد تمبر مالیاتی
        //   calculator.late_payment_rate → نرخ خسارت تأخیر
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'number', 'boolean', 'json'])->default('string');
            $table->string('label')->nullable();                             // برچسب فارسی
            $table->text('description')->nullable();
            $table->string('group')->default('general');                     // pricing | calculator | general
            $table->timestamps();
        });

        // ─── ساعات کاری معمول وکیل ──────────────────────────────────────────
        // day_of_week: 0=شنبه ... 5=پنجشنبه
        Schema::create('lawyer_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['lawyer_id', 'day_of_week']);
        });

        // ─── روزهای استثنا (تعطیلات / سفر) ─────────────────────────────────
        Schema::create('lawyer_schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->date('exception_date');
            $table->boolean('is_available')->default(false);                // false = تعطیل
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['lawyer_id', 'exception_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyer_schedule_exceptions');
        Schema::dropIfExists('lawyer_schedules');
        Schema::dropIfExists('settings');
    }
};
