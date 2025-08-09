<?php

use App\Enums\Cargo\CargoStatus;
use App\Enums\Cargo\FareType;
use App\Enums\Cargo\LocationType;
use App\Enums\Cargo\ReservationStatus;
use App\Enums\Cargo\Type;
use App\Models\Cargo;
use App\Models\CargoType;
use App\Models\City;
use App\Models\Province;
use App\Models\Driver;
use App\Models\Packing;
use App\Models\Insurance;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام بسته بندی');
            $table->timestamps();
        });

        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام بیمه');
            $table->mediumInteger('code')->comment('کد بیمه');
            $table->decimal('coefficient')->nullable()->comment('ضریب بیمه');
            $table->timestamps();
        });

        Schema::create('cargo_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نوع بار');
            $table->mediumInteger('code')->comment('کد بار');
            $table->timestamps();
        });

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->comment('شناسه صاحب بار')->index()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('weight')->nullable()->comment('وزن بار بر اساس تن');
            $table->unsignedSmallInteger('number')->nullable()->comment('تعداد');
            $table->unsignedTinyInteger('thickness')->nullable()->comment('ضخامت بار بر اساس متر');
            $table->unsignedTinyInteger('length')->nullable()->comment('طول بار بر اساس متر');
            $table->unsignedTinyInteger('width')->nullable()->comment('عرض بار بر اساس متر');
            $table->foreignIdFor(Insurance::class)->nullable()->comment('شرکت بیمه')->constrained()->nullOnDelete();
            $table->unsignedInteger('insurance_value')->nullable()->comment('ارزش بیمه');
            $table->unsignedBigInteger('fare')->nullable()->comment('مبلغ کرایه بر حسب ریال');
            $table->unsignedBigInteger('final_fare')->nullable()->comment(' مبلغ کرایه محاسبه شده بر حسب ریال');
            $table->enum('fare_type', FareType::TYPES)->nullable()->comment('نوع پرداخت کرایه');
            $table->enum('type', Type::TYPES)->index()->nullable()->comment('نوع بار');
            $table->timestamp('date_to')->nullable()->comment('تاریخ پایان مناقصه');
            $table->foreignIdFor(CargoType::class)->nullable()->comment('شناسه نوع بار')->constrained()->nullOnDelete();
            $table->foreignIdFor(Packing::class)->nullable()->comment('شناسه نوع بسته‌بندی')->constrained()->nullOnDelete();
            $table->foreignId('assigned_company_id')->nullable()->constrained('users')->comment('شرکت تاییدکننده نهایی');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->timestamps();
        });

        Schema::create('cargo_information', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cargo::class)->constrained()->cascadeOnDelete();
            $table->enum('type', LocationType::TYPES)->nullable()->comment('نوع (مبدا/مقصد)');
            $table->decimal('lat', 10, 7)->nullable()->comment('عرض جغرافیایی');
            $table->decimal('lng', 10, 7)->nullable()->comment('طول جغرافیایی');
            $table->foreignIdFor(Province::class)->nullable()->comment('شناسه استان')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->nullable()->comment('شناسه شهر')->constrained()->nullOnDelete();
            $table->text('description')->nullable()->comment('توضیحات');
            $table->text('address')->nullable()->comment('آدرس دقیق');
            $table->timestamp('date_at')->nullable()->comment('تاریخ  شروع');
            $table->timestamp('date_to')->nullable()->comment('تاریخ پایان');
            $table->timestamps();
        });

        Schema::create('cargo_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'company_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Cargo::class)->constrained()->cascadeOnDelete();
            $table->enum('status', ReservationStatus::STATUSES)->default(ReservationStatus::PENDING)->comment('وضعیت رزرو');
            $table->timestamps();
            $table->unique(['cargo_id', 'company_id']);

        });

        Schema::create('cargo_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Cargo::class)->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('offered_fare')->default(0)->comment('قیمت پیشنهادی');
            $table->enum('status', ReservationStatus::STATUSES)->default(ReservationStatus::PENDING)->comment('وضعیت پیشنهاد');
            $table->text('note')->nullable()->comment('توضیحات بار');
            $table->timestamps();
        });

        Schema::create('partitions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'company_id')->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Cargo::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->foreignIdFor(Driver::class)->nullable()->index()->constrained()->nullOnDelete();
            $table->unsignedInteger('weight')->nullable()->comment('وزن (تن)');
            $table->unsignedBigInteger('fare')->nullable()->comment('کرایه');
            $table->unsignedBigInteger('commission')->nullable()->comment('کمیسیون');
            $table->enum('status', CargoStatus::STATUSES)->default(CargoStatus::FREE)->comment('وضعیت حمل');
            $table->string('havaleFile')->nullable()->comment('فایل حواله');
            $table->string('barnamehFile')->nullable()->comment('فایل بارنامه');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packings');
        Schema::dropIfExists('insurances');
        Schema::dropIfExists('cargo_types');
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('cargo_information');
        Schema::dropIfExists('cargo_reservations');
        Schema::dropIfExists('cargo_bids');
        Schema::dropIfExists('partitions');
    }
};
