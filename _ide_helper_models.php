<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $owner_id شناسه صاحب بار
 * @property int|null $weight وزن بار بر اساس تن
 * @property int|null $number تعداد
 * @property int|null $thickness ضخامت بار بر اساس متر
 * @property int|null $length طول بار بر اساس متر
 * @property int|null $width عرض بار بر اساس متر
 * @property int|null $insurance ارزش بیمه
 * @property int|null $fare مبلغ کرایه بر حسب ریال
 * @property string|null $fare_type نوع پرداخت کرایه
 * @property string $type نوع بار
 * @property int|null $cargo_type_id شناسه نوع بار
 * @property int|null $packing_id شناسه نوع بسته‌بندی
 * @property string|null $description توضیحات
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CargoBid> $cargoBids
 * @property-read int|null $cargo_bids_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CargoInformation> $cargoInformation
 * @property-read int|null $cargo_information_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CargoReservation> $cargoReservations
 * @property-read int|null $cargo_reservations_count
 * @property-read \App\Models\CargoType|null $cargoType
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Packing|null $packing
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Partition> $partitions
 * @property-read int|null $partitions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereCargoTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereFareType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo wherePackingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereThickness($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereWidth($value)
 * @mixin \Eloquent
 * @property string|null $date_to تاریخ پایان مناقصه
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereDateTo($value)
 */
	class Cargo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $cargo_id
 * @property int $offered_fare قیمت پیشنهادی
 * @property string $status وضعیت پیشنهاد
 * @property string|null $note توضیحات بار
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo|null $cargo
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereOfferedFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoBid whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CargoBid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cargo_id
 * @property string|null $type نوع (مبدا/مقصد)
 * @property float|null $lat عرض جغرافیایی
 * @property float|null $lng طول جغرافیایی
 * @property int|null $city_id
 * @property string|null $description توضیحات
 * @property string|null $address آدرس دقیق
 * @property int|null $date_at تاریخ و ساعت شروع
 * @property int|null $date_to تاریخ و ساعت پایان
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo $cargo
 * @property-read \App\Models\City|null $city
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $province_id شناسه استان
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoInformation whereProvinceId($value)
 */
	class CargoInformation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int $cargo_id
 * @property string $status وضعیت رزرو
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo $cargo
 * @property-read \App\Models\User $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoReservation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CargoReservation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name نوع بار
 * @property int $code کد بار
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cargo> $cargos
 * @property-read int|null $cargos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CargoType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CargoType extends \Eloquent {}
}

namespace App\Models{
/**
 * Class City
 *
 * @property int $id
 * @property string $code کد شهر
 * @property string $name نام شهر
 * @property int $province_code کد استان
 * @property float|null $latitude
 * @property float|null $longitude
 * @property-read Province $province استان مربوطه
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class City extends \Eloquent {}
}

namespace App\Models{
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
 * @mixin \Eloquent
 * @property int|null $province_id شناسه استان
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereProvinceId($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $title عنوان
 * @property string $description توضیحات
 * @property mixed $status وضعیت
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUserId($value)
 * @mixin \Eloquent
 */
	class Complaint extends \Eloquent {}
}

namespace App\Models{
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
 * @mixin \Eloquent
 * @property int|null $province_id شناسه استان
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereProvinceId($value)
 */
	class Driver extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name نام بیمه
 * @property int $code کد بیمه
 * @property string|null $coefficient ضریب بیمه
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Insurance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name نام بسته بندی
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cargo> $cargos
 * @property-read int|null $cargos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Packing extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $cargo_id
 * @property int|null $driver_id
 * @property int|null $weight وزن (تن)
 * @property int|null $fare کرایه
 * @property int|null $commission کمیسیون
 * @property string $status وضعیت حمل
 * @property string|null $havaleFile فایل حواله
 * @property string|null $barnamehFile فایل بارنامه
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cargo|null $cargo
 * @property-read \App\Models\User|null $company
 * @property-read \App\Models\Driver|null $driver
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereBarnamehFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereHavaleFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partition whereWeight($value)
 * @mixin \Eloquent
 */
	class Partition extends \Eloquent {}
}

namespace App\Models{
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
 * @mixin \Eloquent
 * @property int|null $province_id شناسه استان
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOwner whereProvinceId($value)
 */
	class ProductOwner extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Province
 *
 * @property int $id
 * @property int $code کد استان
 * @property string $name نام استان
 * @property-read Collection<int, City> $cities لیست شهرهای این استان
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property int $partition_id شناسه پارتیشن
 * @property int|null $rating امتیاز
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Partition $partition
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating wherePartitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rating whereUserId($value)
 * @mixin \Eloquent
 */
	class Rating extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Driver|null $driver
 * @property-read \App\Models\ProductOwner|null $productOwner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id شناسه کاربر
 * @property string $code کد اعتبارسنجی
 * @property Carbon|null $expires_at اعتبار کد
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $remaining_seconds
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUserId($value)
 * @mixin \Eloquent
 */
	class UserOtp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $smart_number شماره هوشمند
 * @property int $cost_center مرکز هزینه
 * @property int $plate_first قسمت اول پلاک
 * @property int $plate_second قسمت دوم پلاک
 * @property int $plate_third قسمت سوم پلاک(کد استان)
 * @property string $plate_letter حرف پلاک
 * @property string $plate_type نوع پلاک
 * @property string $status وضعیت
 * @property string $type نوع خودرو
 * @property int $vehicle_detail_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VehicleDetail $vehicleDetail
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCostCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateThird($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePlateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSmartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereVehicleDetailId($value)
 * @mixin \Eloquent
 */
	class Vehicle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $brand برند
 * @property string $name مدل
 * @property string $motorCode شناسه موتور
 * @property string $bodyCode شناسه بدنه
 * @property int $year سال ساخت
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicles
 * @property-read int|null $vehicles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereBodyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereMotorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDetail whereYear($value)
 * @mixin \Eloquent
 */
	class VehicleDetail extends \Eloquent {}
}

