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
        Schema::create('cargos', function (Blueprint $table) {
            //اطلاعات بار
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
