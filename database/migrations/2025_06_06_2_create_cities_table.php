<?php

use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code')->index()->comment('کد استان ');
            $table->string('name')->comment('نام استان ');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code')->index()->comment('کد شهر ');
            $table->string('name')->comment('نام شهر ');
            $table->foreignIdFor(State::class)->comment('شناسه استان ')->constrained()->cascadeOnDelete();
            $table->double('latitude')->comment('عرض جغرافیایی');
            $table->double('longitude')->comment('طول جغرافیایی');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('cities');
    }
};
