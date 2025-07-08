<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOwner extends Model
{
    protected $fillable = [
        'user_id',
        'product_owner_type',
        'national_code',
        'bank_name',
        'sheba_number',
        'city_id',
        'registration_id',
        'national_id',
        'rahdari_code',
        'agent_name',
        'agent_national_code',
        'agent_phone_number',
        'manager_name',
        'manager_national_code',
        'manager_phone_number',
        'address',
        'document'
    ];

    public function user():  BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city():  BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
