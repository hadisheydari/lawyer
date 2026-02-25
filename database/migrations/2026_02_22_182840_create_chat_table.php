<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * چت — دو سناریو:
 *   ۱. مشتری ساده: consultation_id دارد (پرداختی، محدود)
 *   ۲. موکل ویژه:  case_id دارد (نامحدود)
 *
 * sender_id در chat_messages به هر دو user و lawyer اشاره می‌کنه
 * (sender_type برای تشخیص استفاده می‌شه — بدون polymorphic FK)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');

            // مشتری ساده
            $table->foreignId('consultation_id')
                ->nullable()
                ->constrained('consultations')
                ->onDelete('set null');

            // موکل ویژه
            $table->foreignId('case_id')
                ->nullable()
                ->constrained('cases')
                ->onDelete('set null');

            $table->string('title')->nullable();
            $table->enum('status', ['active', 'closed', 'archived'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['lawyer_id', 'status']);
            $table->index('last_message_at');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained('chat_conversations')
                ->onDelete('cascade');

            // sender_id می‌تونه user یا lawyer باشه — از sender_type تشخیص داده می‌شه
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', ['user', 'lawyer']);

            $table->text('message');
            $table->json('attachments')->nullable();                         // [{name, path, type, size}]

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('conversation_id');
            $table->index(['conversation_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
    }
};
