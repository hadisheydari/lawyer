<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->comment('شناسه صاحب بار (کاربر ثبت کننده)')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['reserve', 'rfq', 'free'])->nullable()->comment('نوع بار');
            $table->unsignedSmallInteger('weight')->nullable()->comment('وزن بار بر اساس تن');
            $table->unsignedSmallInteger('number')->nullable()->comment('تعداد');
            $table->unsignedTinyInteger('thickness')->nullable()->comment('ضخامت بار بر اساس متر');
            $table->unsignedTinyInteger('length')->nullable()->comment('طول بار بر اساس ‌متر');
            $table->unsignedTinyInteger('width')->nullable()->comment('عرض بار بر اساس ‌متر');
            $table->unsignedInteger('insurance')->nullable()->comment('ارزش بیمه بر حسب واحد مشخص شده');
            $table->unsignedBigInteger('fare')->nullable()->comment('مبلغ کرایه بر حسب ریال');
            $table->enum('fare_type', ['service', 'tonnage'])->nullable()->comment('نوع پرداخت کرایه');
            $table->foreignId('cargo_type_id')->nullable()->comment('شناسه نوع بار ')->constrained('cargo_types')->cascadeOnDelete();
            $table->foreignId('packing_id')->nullable()->comment('شناسه نوع بسته بندی  ')->constrained('packings')->cascadeOnDelete();
            $table->text('description')->nullable()->comment('توضیحات اضافی درباره بار');

            $table->timestamps();

            $table->index('owner_id');
            $table->index('type');

        });
        Schema::create('cargo_types', function (Blueprint $table) {
            //انواع بار
            $table->id();
            $table->string('name')->nullable()->comment(' نوع بار  ');
            $table->mediumInteger('code')->nullable()->comment(' کد بار ');
            $table->timestamps();
        });
        Schema::create('cargo_information', function (Blueprint $table) {
            //اطلاعات مبدا و مقصد بار
            $table->id();
            $table->foreignId('cargo_id')->comment('شناسه بار ')->constrained('cargos')->cascadeOnDelete();
            $table->enum('type', ['origin', 'destination'])->nullable()->comment('نوع مبدا یا مقصد  ');
            $table->decimal('lat', 10, 7)->nullable()->comment('عرض جغرافیایی ');
            $table->decimal('lng', 10, 7)->nullable()->comment('طول جغرافیایی ');
            $table->foreignId('city_id')->nullable()->comment('شناسه شهر ')->constrained('cities')->cascadeOnDelete();
            $table->text('description')->nullable()->comment('توضیحات مربوط ');
            $table->text('address')->nullable()->comment('آدرس دقیق');
            $table->timestamp('date_at')->nullable()->comment('تاریخ و ساعت شروع ');
            $table->timestamp('date_to')->nullable()->comment('تاریخ و ساعت پایان ');
            $table->timestamps();
        });
        Schema::create('cargo_reservations', function (Blueprint $table) {
            // رزرو بار برای شرکت حمل
            $table->id();
            $table->foreignId('cargo_id')->comment('شناسه ی بار ')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->comment('شناسه ی شرکت حمل  ')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('وضعیت تایید و یا رد بار ');
            $table->timestamps();
        });
        Schema::create('cargo_bids', function (Blueprint $table) {
            //  مناقصه شرکت حمل برای بار
            $table->id();
            $table->foreignId('cargo_id')->nullable()->comment('شناسه ی بار ')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('شناسه ی شرکت حمل ')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('offered_fare')->nullable()->comment('قیمت پیشنهادی برای بار   ');
            $table->text('note')->nullable()->comment('توضیحات بار ');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('وضعیت تایید و یا رد پیشنهاد ');
            $table->timestamps();
        });
        Schema::create('partitions', function (Blueprint $table) {
            // پارتیشن ها
            $table->id();
            $table->foreignId('cargo_id')->nullable()->comment('شناسه ی بار ')->constrained('cargos')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('شناسه ی شرکت حمل  ')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->comment('شناسه ی راننده  ')->constrained('drivers')->cascadeOnDelete();
            $table->unsignedInteger('weight')->nullable()->comment(' وزن بار بر اساس تن ');
            $table->unsignedBigInteger('fare')->nullable()->comment(' قیمت کرایه ');
            $table->unsignedBigInteger('commission')->nullable()->comment(' کمیسیو ');
            $table->enum('status', ['free', 'reserved', 'havale', 'barnameh', 'delivered'])->default('free')->comment(' وضعیت حمل بار  ');
            $table->string('havaleFile')->nullable()->comment(' فایل حواله   ');
            $table->string('barnamehFile')->nullable()->comment(' فایل بارنامه   ');
            $table->timestamps();

            $table->index('cargo_id');
            $table->index('company_id');
            $table->index('driver_id');

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
            $table->decimal('coefficient')->nullable()->comment('ضریب بیمه');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
