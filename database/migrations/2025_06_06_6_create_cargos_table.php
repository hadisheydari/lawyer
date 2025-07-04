<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول شرکت‌های حمل (کاربرهای خاص)
        Schema::create('cargo_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('نوع بار');
            $table->mediumInteger('code')->nullable()->comment('کد بار');
            $table->timestamps();
        });

        Schema::create('packings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام بسته بندی');
            $table->timestamps();
        });

        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('نام بیمه');
            $table->mediumInteger('code')->nullable()->comment('کد بیمه');
            $table->decimal('coefficient', 8, 2)->nullable()->comment('ضریب بیمه');
            $table->timestamps();
        });

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->comment('شناسه صاحب بار')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['reserve', 'rfq', 'free'])->nullable()->comment('نوع بار');
            $table->unsignedSmallInteger('weight')->nullable()->comment('وزن بار بر اساس تن');
            $table->unsignedSmallInteger('number')->nullable()->comment('تعداد');
            $table->unsignedTinyInteger('thickness')->nullable()->comment('ضخامت بار بر اساس متر');
            $table->unsignedTinyInteger('length')->nullable()->comment('طول بار بر اساس متر');
            $table->unsignedTinyInteger('width')->nullable()->comment('عرض بار بر اساس متر');
            $table->unsignedInteger('insurance')->nullable()->comment('ارزش بیمه');
            $table->unsignedBigInteger('fare')->nullable()->comment('مبلغ کرایه بر حسب ریال');
            $table->enum('fare_type', ['service', 'tonnage'])->nullable()->comment('نوع پرداخت کرایه');
            $table->foreignId('cargo_type_id')->nullable()->comment('شناسه نوع بار')->constrained('cargo_types')->nullOnDelete();
            $table->foreignId('packing_id')->nullable()->comment('شناسه نوع بسته‌بندی')->constrained('packings')->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات اضافی');
            $table->timestamps();

            $table->index('owner_id');
            $table->index('type');
        });

        Schema::create('cargo_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->enum('type', ['origin', 'destination'])->nullable()->comment('نوع (مبدا/مقصد)');
            $table->decimal('lat', 10, 7)->nullable()->comment('عرض جغرافیایی');
            $table->decimal('lng', 10, 7)->nullable()->comment('طول جغرافیایی');
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('address')->nullable()->comment('آدرس دقیق');
            $table->timestamp('date_at')->nullable()->comment('تاریخ و ساعت شروع');
            $table->timestamp('date_to')->nullable()->comment('تاریخ و ساعت پایان');
            $table->timestamps();
        });

        Schema::create('cargo_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('وضعیت رزرو');
            $table->timestamps();
        });

        Schema::create('cargo_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->nullable()->constrained('cargos')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('offered_fare')->nullable()->comment('قیمت پیشنهادی');
            $table->text('note')->nullable()->comment('توضیحات بار');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('وضعیت پیشنهاد');
            $table->timestamps();
        });

        Schema::create('partitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->nullable()->constrained('cargos')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->unsignedInteger('weight')->nullable()->comment('وزن (تن)');
            $table->unsignedBigInteger('fare')->nullable()->comment('کرایه');
            $table->unsignedBigInteger('commission')->nullable()->comment('کمیسیون');
            $table->enum('status', ['free', 'reserved', 'havale', 'barnameh', 'delivered'])->default('free')->comment('وضعیت حمل');
            $table->string('havaleFile')->nullable()->comment('فایل حواله');
            $table->string('barnamehFile')->nullable()->comment('فایل بارنامه');
            $table->timestamps();

            $table->index('cargo_id');
            $table->index('company_id');
            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partitions');
        Schema::dropIfExists('cargo_bids');
        Schema::dropIfExists('cargo_reservations');
        Schema::dropIfExists('cargo_information');
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('cargo_types');
        Schema::dropIfExists('packings');
        Schema::dropIfExists('insurances');
    }
};
