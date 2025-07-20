<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string|null $product_owner_type real -> حقیقی, legal -> حقوقی
 * @property string $national_code کد ملی
 * @property string|null $bank_name نام بانک
 * @property string|null $sheba_number شماره شبا
 * @property int|null $city_id شناسه شهر
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereAgentNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereAgentPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereManagerNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereManagerPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereNationalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereProductOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereRahdariCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereShebaNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereUserId($value)
 *
 * @mixin \Eloquent
 */
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
}
