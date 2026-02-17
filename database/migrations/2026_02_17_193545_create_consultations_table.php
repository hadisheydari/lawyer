<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['call', 'chat', 'appointment'])->default('call');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 0)->default(0);
            $table->integer('duration_minutes')->nullable(); // برای تماس
            $table->timestamp('scheduled_at')->nullable(); // برای نوبت‌دهی
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->text('notes')->nullable(); // یادداشت وکیل
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
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
