<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Migrations\Traits\CommonFieldsCompanyOwner;


return new class extends Migration
{
    use CommonFieldsCompanyOwner;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_owners', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('productOwnerType')->default(0)->nullable();
            $table->string('nationalCode')->nullable();
            $table->string('bankName')->nullable();
            $table->string('shebaNumber')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('cascade');
            $this->addCommonFields($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_owners');
    }
};
