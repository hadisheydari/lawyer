<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TAG_LETTERS = [
        'الف', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ',
        'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض',
        'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل',
        'م', 'ن', 'و', 'ه', 'ی',
    ];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            // وسیله نقلیه
            $table->id();
            $table->unsignedTinyInteger('first_tag')->nullable()->comment(' قسمت اول پلاک ');
            $table->enum('second_tag', self::TAG_LETTERS)->default('ع');
            $table->unsignedSmallInteger('third_tag')->nullable()->comment(' قسمت دم پلاک ');
            $table->unsignedTinyInteger('series_tag')->nullable()->comment(' قسمت سوم پلاک ');
            $table->unsignedBigInteger('smart_number')->nullable()->comment(' شماره هوشمند ')->index();
            $table->enum('tag_type', ['general', 'governmental', 'personal'])->default('personal')->comment(' نوع پلاک ');
            $table->enum('status', ['active', 'not_active'])->default('active');
            $table->foreignId('type_id')->constrained('vehicle_types')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
