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
        Schema::create('cities', function (Blueprint $table) {
            //شهر ها
            $table->id();
            $table->foreignId('province_id')->nullable()->comment('استان مربوطه')->constrained('provinces')->onDelete('cascade');
            $table->string('name')->nullable()->comment('نام شهر');
            $table->decimal('lat', 10, 7)->nullable()->comment('عرض جغرافیایی شهر');
            $table->decimal('lng', 10, 7)->nullable()->comment('طول جغرافیایی شهر');
            $table->timestamps();

            $table->unique(['province_id', 'name'], 'city_unique_in_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
