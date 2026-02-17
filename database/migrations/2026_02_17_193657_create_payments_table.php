<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('payable'); // consultation یا subscription
            $table->string('tracking_code')->unique(); // کد پیگیری زرین‌پال
            $table->string('authority')->nullable(); // Authority زرین‌پال
            $table->string('ref_id')->nullable(); // RefID زرین‌پال
            $table->decimal('amount', 10, 0);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('gateway', ['zarinpal', 'mellat', 'parsian'])->default('zarinpal');
            $table->text('description')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('tracking_code');
            $table->index('authority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
