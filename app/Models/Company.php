<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $company_type normal -> معمولی, large_scale -> بزرگ مقیاس
 * @property string|null $registration_id شناسه ثبت
 * @property string|null $national_id شناسه ملی
 * @property string|null $rahdari_code کد راهداری
 * @property string|null $agent_name نام نماینده
 * @property string|null $agent_national_code کدملی نماینده
 * @property string|null $agent_phone_number شماره نماینده
 * @property string|null $manager_name نام مدیرعامل
 * @property string|null $manager_national_code کدملی مدیرعامل
 * @property string|null $manager_phone_number شماره مدیرعامل
 * @property string|null $address آدرس
 * @property string|null $document مدارک
 * @property int|null $city_id شناسه شهر
 * @property int|null $province_id شناسه استان
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Driver> $drivers
 * @property-read int|null $drivers_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAgentNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAgentPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereManagerNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereManagerPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereNationalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereRahdariCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereProvinceId($value)
 * @property-read \App\Models\Province|null $province
 * @mixin \Eloquent
 */
class Company extends Model
{
    protected $fillable = [
        'user_id',
        'company_type',
        'city_id',
        'province_id',
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
        'document',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(province::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }
}
