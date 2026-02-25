<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * پرداخت‌ها — polymorphic
 *
 * payable_type / payable_id:
 *   App\Models\Consultation     → پرداخت مشاوره مشتری ساده
 *   App\Models\CaseInstallment  → پرداخت قسط پرونده موکل ویژه
 *
 * status:
 *   pending  → در انتظار پرداخت
 *   paid     → پرداخت شده
 *   failed   → ناموفق
 *   refunded → برگشت داده شده
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->morphs('payable');                                      // consultation یا installment

            $table->string('tracking_code')->unique();                      // کد پیگیری داخلی
            $table->string('authority')->nullable();                        // Authority زرین‌پال
            $table->string('ref_id')->nullable();                           // RefID زرین‌پال

            $table->decimal('amount', 12, 0);
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

        // حالا که payments وجود داره، FK رو به consultations اضافه می‌کنیم
        Schema::table('consultations', function (Blueprint $table) {
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });
        Schema::dropIfExists('payments');
    }
};
