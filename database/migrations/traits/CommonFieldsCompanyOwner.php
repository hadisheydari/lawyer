<?php

namespace Database\Migrations\Traits;

use Illuminate\Database\Schema\Blueprint;

trait CommonFieldsCompanyOwner
{
    public function addCommonFields(Blueprint $table)
    {
        $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('registrationId')->nullable();
        $table->string('nationalId')->nullable();
        $table->string('rahdariCode')->nullable();
        $table->string('agentName')->nullable();
        $table->string('agentNationalCode')->nullable();
        $table->string('agentPhoneNumber')->nullable();
        $table->string('managerName')->nullable();
        $table->string('managerNationalCode')->nullable();
        $table->string('managerPhoneNumber')->nullable();
        $table->string('address')->nullable();
        $table->string('document')->nullable();
    }
}
