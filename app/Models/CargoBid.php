<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 *
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
 *
 * @mixin \Eloquent
 */
class CargoBid extends Model
{
    protected $fillable = [
        'cargo_id',
        'company_id',
        'offered_fare',
        'note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'offered_fare' => 'integer',
        ];
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
