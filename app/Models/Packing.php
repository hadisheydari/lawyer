<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name نام بسته بندی
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cargo> $cargos
 * @property-read int|null $cargos_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packing whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Packing extends Model
{
    protected $fillable = [
        'name',
    ];

    public function cargos(): HasMany
    {
        return $this->hasMany(Cargo::class);
    }
}
