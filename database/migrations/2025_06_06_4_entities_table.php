<?php

use App\Models\City;
use App\Models\Company;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('شناسه کاربر ')->constrained()->cascadeOnDelete();
            $table->enum('company_type', ['normal', 'large_scale'])->default('normal')->comment('normal -> معمولی, large_scale -> بزرگ مقیاس');
            $table->foreignIdFor(City::class)->nullable()->comment('شناسه شهر')->constrained()->cascadeOnDelete();
            $table->string('registration_id')->nullable()->comment('شناسه ثبت');
            $table->string('national_id')->nullable()->comment('شناسه ملی');
            $table->string('rahdari_code')->nullable()->comment('کد راهداری');
            $table->string('agent_name')->nullable()->comment('نام نماینده');
            $table->string('agent_national_code')->nullable()->comment('کدملی نماینده');
            $table->string('agent_phone_number')->nullable()->comment('شماره نماینده');
            $table->string('manager_name')->nullable()->comment('نام مدیرعامل');
            $table->string('manager_national_code')->nullable()->comment('کدملی مدیرعامل');
            $table->string('manager_phone_number')->nullable()->comment('شماره مدیرعامل');
            $table->string('address')->nullable()->comment('آدرس');
            $table->string('document')->nullable()->comment('مدارک');
            $table->timestamps();
        });

        Schema::create('product_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('شناسه کاربر ')->constrained()->cascadeOnDelete();
            $table->enum('product_owner_type', ['real', 'legal'])->nullable()->comment('real -> حقیقی, legal -> حقوقی');
            $table->string('national_code')->comment('کد ملی');
            $table->string('bank_name')->nullable()->comment('نام بانک');
            $table->string('sheba_number')->nullable()->comment('شماره شبا');
            $table->foreignIdFor(City::class)->nullable()->comment('شناسه شهر ')->constrained()->cascadeOnDelete();
            $table->string('registration_id')->nullable()->comment('شناسه ثبت');
            $table->string('national_id')->nullable()->comment('شناسه ملی');
            $table->string('rahdari_code')->nullable()->comment('کد راهداری');
            $table->string('agent_name')->nullable()->comment('نام نماینده');
            $table->string('agent_national_code')->nullable()->comment('کدملی نماینده');
            $table->string('agent_phone_number')->nullable()->comment('شماره نماینده');
            $table->string('manager_name')->nullable()->comment('نام مدیرعامل');
            $table->string('manager_national_code')->nullable()->comment('کدملی مدیرعامل');
            $table->string('manager_phone_number')->nullable()->comment('شماره مدیرعامل');
            $table->string('address')->nullable()->comment('آدرس');
            $table->string('document')->nullable()->comment('مدارک');
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('شناسه کاربر ')->constrained()->cascadeOnDelete();
            $table->string('national_code')->comment('کدملی');
            $table->date('birth_date')->nullable()->comment('تاریخ تولد');
            $table->string('father_name')->nullable()->comment('نام پدر');
            $table->string('certificate_number')->nullable()->comment('شماره گواهینامه');
            $table->enum('property', ['owned', 'non_owned'])->nullable()->comment('owned -> ملکی, non_owned -> غیرملکی');
            $table->string('national_card_file')->nullable()->comment('فایل کارت ملی');
            $table->string('smart_card_file')->nullable()->comment('فایل کارت هوشمند');
            $table->string('certificate_file')->nullable()->comment('فایل گواهینامه');
            $table->foreignIdFor(Company::class)->nullable()->comment('شناسه شرکت حمل ')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->nullable()->comment('شناسه شهر ')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('product_owners');
        Schema::dropIfExists('drivers');
    }
};
