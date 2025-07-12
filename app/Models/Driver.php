<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'national_code',
        'birth_date',
        'father_name',
        'certificate_number',
        'property',
        'national_card_file',
        'smart_card_file',
        'certificate_file',
        'company_id',
        'city_id',
    ];

    public function user():  BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company():  BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function city():  BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
