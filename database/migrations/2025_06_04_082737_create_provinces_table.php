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
        // استان ها
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('نام استان');
            $table->string('code')->unique()->comment('کد استان');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
