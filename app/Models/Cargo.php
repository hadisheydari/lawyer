<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $owner_id شناسه صاحب بار
 * @property int|null $weight وزن بار بر اساس تن
 * @property int|null $number تعداد
 * @property int|null $thickness ضخامت بار بر اساس متر
 * @property int|null $length طول بار بر اساس متر
 * @property int|null $width عرض بار بر اساس متر
 * @property int|null $insurance_value ارزش بیمه
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
 * @property string|null $date_to تاریخ پایان مناقصه
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereDateTo($value)
 * @property int|null $insurance_id شرکت بیمه
 * @property int|null $final_fare  مبلغ کرایه محاسبه شده بر حسب ریال
 * @property string|null $date_at تاریخ شروع مناقصه
 * @property int|null $assigned_company_id
 * @property-read \App\Models\User|null $company
 * @property-read \App\Models\CargoInformation|null $destination
 * @property-read mixed $partition_weight
 * @property-read mixed $reservation_status
 * @property-read mixed $reservations
 * @property-read \App\Models\Insurance|null $insurance
 * @property-read \App\Models\CargoInformation|null $origin
 * @property-read \App\Models\CargoReservation|null $reserve
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CargoReservation> $rfq
 * @property-read int|null $rfq_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereAssignedCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereDateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereFinalFare($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereInsuranceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereInsuranceValue($value)
 * @mixin \Eloquent
 */
class Cargo extends Model
{
    protected $fillable = [
        'owner_id',
        'type',
        'weight',
        'number',
        'thickness',
        'length',
        'width',
        'insurance_id',
        'insurance_value',
        'fare',
        'final_fare',
        'date_at',
        'date_to',
        'fare_type',
        'cargo_type_id',
        'packing_id',
        'assigned_company_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'fare' => 'integer',
            'final_fare' => 'integer',
            'weight' => 'integer',
            'number' => 'integer',
            'thickness' => 'integer',
            'length' => 'integer',
            'width' => 'integer',
            'type' => 'string',
            'fare_type' => 'string',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_company_id');
    }

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class);
    }

    public function packing(): BelongsTo
    {
        return $this->belongsTo(Packing::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }
    public function origin() :HasOne
    {
        return $this->hasOne(CargoInformation::class)->where('type' , 'origin');
    }
    public function destination() :HasOne
    {
        return $this->hasOne(CargoInformation::class)->where('type' , 'destination');
    }

    public function rfq(): HasMany
    {
        return $this->hasMany(CargoReservation::class);
    }

    public function reserve(): HasOne
    {
        return $this->hasOne(CargoReservation::class);
    }

    public function cargoBids(): HasMany
    {
        return $this->hasMany(CargoBid::class);
    }

    public function partitions(): HasMany
    {
        return $this->hasMany(Partition::class);
    }


    public function getReservationStatusAttribute()
    {
        $type = $this->type;

        if ($this->assigned_company_id) {
            return 'accepted';
        }

        if (in_array($type, ['rfq', 'reserve'])) {
            $relation = $this->$type;

            if ($relation instanceof \Illuminate\Database\Eloquent\Collection) {
                // حالت کالکشن (مثلاً rfq)
                $statuses = $relation->pluck('status')->unique();

                if ($statuses->count() === 1 && $statuses->contains('rejected')) {
                    return 'rejected';
                }
                return 'pending';
            } elseif ($relation instanceof \Illuminate\Database\Eloquent\Model) {
                if ($relation->status === 'rejected') {
                    return 'rejected';
                }
                return 'pending';
            }
        }

        return 'pending';
    }


    public function getReservationsAttribute()
    {
        if ($this->type === 'rfq') {
            return $this->rfq;
        }
        if ($this->type === 'reserve') {
            return $this->reserve ? collect([$this->reserve]) : collect([]);
        }
        return collect([]);
    }

    public function getPartitionWeightAttribute()
    {
        return $this->partitions()->sum('weight');
    }
}
