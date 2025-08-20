<?php

use App\Models\Partition;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Rating\RatingEnum;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('شناسه کاربر')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Partition::class)->comment('شناسه پارتیشن')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->nullable()->comment('امتیاز');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
