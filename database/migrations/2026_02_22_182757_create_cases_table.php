<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * پرونده‌ها و جداول وابسته — مخصوص موکلین ویژه
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── پرونده اصلی ────────────────────────────────────────────────────
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();                         // LAW-1404-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();

            /*
             * current_status:
             *   active  → در جریان
             *   on_hold → متوقف موقت
             *   closed  → ختم دادرسی
             *   won     → رأی به نفع موکل
             *   lost    → رأی علیه موکل
             */
            $table->enum('current_status', ['active', 'on_hold', 'closed', 'won', 'lost'])
                ->default('active');

            $table->decimal('total_fee', 12, 0);                            // کل حق‌الوکاله
            $table->decimal('paid_amount', 12, 0)->default(0);              // پرداخت‌شده تاکنون

            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'current_status']);
            $table->index(['lawyer_id', 'current_status']);
        });

        // ─── تاریخچه وضعیت پرونده (نوار پیشرفت موکل) ──────────────────────
        Schema::create('case_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');

            $table->string('status_title');                                 // "ارجاع به شعبه ۱۲"
            $table->text('description')->nullable();                        // گزارش برای موکل
            $table->text('private_notes')->nullable();                      // یادداشت خصوصی وکیل
            $table->date('status_date');

            $table->timestamps();

            $table->index('case_id');
        });

        // ─── اسناد و مدارک پرونده ───────────────────────────────────────────
        Schema::create('case_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('status_log_id')
                ->nullable()
                ->constrained('case_status_logs')
                ->onDelete('set null');

            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable();                        // pdf, jpg, docx
            $table->unsignedInteger('file_size')->nullable();               // بایت

            // آپلود کننده: lawyer یا user
            $table->enum('uploader_type', ['lawyer', 'user']);
            $table->unsignedBigInteger('uploader_id');

            $table->boolean('is_visible_to_client')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('case_id');
        });

        // ─── اقساط حق‌الوکاله ───────────────────────────────────────────────
        Schema::create('case_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedTinyInteger('installment_number');              // ۱، ۲، ۳، ...
            $table->decimal('amount', 12, 0);
            $table->date('due_date');                                        // سررسید

            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamp('paid_at')->nullable();

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['case_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_installments');
        Schema::dropIfExists('case_documents');
        Schema::dropIfExists('case_status_logs');
        Schema::dropIfExists('cases');
    }
};
