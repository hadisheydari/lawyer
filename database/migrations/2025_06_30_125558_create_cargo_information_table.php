<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargo_information', function (Blueprint $table) {
            //اطلاعات مبدا و مقصد بار
            $table->id();
            $table->foreignId('cargo_id')->comment('شناسه بار ')->constrained('cargos')->cascadeOnDelete();
            $table->enum('type', ['origin', 'destination'])->nullable()->comment('نوع مبدا یا مقصد  ');
            $table->decimal('lat', 10, 7)->nullable()->comment('عرض جغرافیایی ');
            $table->decimal('lng', 10, 7)->nullable()->comment('طول جغرافیایی ');
            $table->foreignId('city_id')->nullable()->comment('شناسه شهر ')->constrained('cities')->cascadeOnDelete();
            $table->text('description')->nullable()->comment('توضیحات مربوط ');;
            $table->text('address')->nullable()->comment('آدرس دقیق');
            $table->timestamp('date_at')->nullable()->comment('تاریخ و ساعت شروع ');
            $table->timestamp('date_to')->nullable()->comment('تاریخ و ساعت پایان ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_information');
    }
};
