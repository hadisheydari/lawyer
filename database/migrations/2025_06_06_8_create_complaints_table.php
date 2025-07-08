<?php

use App\Enums\Complaint\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('شناسه کاربر')->constrained()->cascadeOnDelete();
            $table->string('title')->comment('عنوان');
            $table->string('description')->comment('توضیحات');
            $table->enum('status', Status::STATUSES)->default(Status::NOT_READ)->comment('وضعیت');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
