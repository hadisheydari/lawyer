<?php

use App\Enums\Entity\CompanyType;
use App\Enums\Entity\ProductOwnerType;
use App\Enums\Entity\PropertyType;
use App\Models\City;
use App\Models\Province;
use App\Models\Company;
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
            $table->enum('company_type', CompanyType::TYPES)->default(CompanyType::NORMAL)->comment('normal -> معمولی, large_scale -> بزرگ مقیاس');
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
            $table->foreignIdFor(City::class)->nullable()->comment('شناسه شهر')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Province::class)->nullable()->comment('شناسه استان')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('companies');

    }
};
