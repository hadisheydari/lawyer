<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $national_code کدملی
 * @property string|null $birth_date تاریخ تولد
 * @property string|null $father_name نام پدر
 * @property string|null $certificate_number شماره گواهینامه
 * @property string|null $property owned -> ملکی, non_owned -> غیرملکی
 * @property string|null $national_card_file فایل کارت ملی
 * @property string|null $smart_card_file فایل کارت هوشمند
 * @property string|null $certificate_file فایل گواهینامه
 * @property int|null $company_id شناسه شرکت حمل
 * @property int|null $city_id شناسه شهر
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCertificateFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCertificateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereNationalCardFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereSmartCardFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereUserId($value)
 *
 * @mixin \Eloquent
 */
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
