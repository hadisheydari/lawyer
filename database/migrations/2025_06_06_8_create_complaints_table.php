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
            $table->foreignId('complainant_id')->constrained('users')->cascadeOnDelete()->comment('شاکی');
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete()->comment('شناسه ی کاربر دریافت کننده');
            $table->string('title')->comment('عنوان شکایت');
            $table->text('description')->comment('توضیحات شکایت');
            $table->enum('status', Status::STATUSES)->default(Status::NOT_READ)->comment('وضعیت');            $table->timestamps();
        });

        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete()->comment('شناسه شکایت');
            $table->text('message')->comment('متن پاسخ');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('complaint_responses');

    }
};
