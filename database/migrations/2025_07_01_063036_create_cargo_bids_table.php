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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_bids');
    }
};
