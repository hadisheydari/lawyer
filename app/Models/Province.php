<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Province extends Model
{
    public const TABLE = 'provinces';

    public const CODE = 'code';

    public const NAME = 'name';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array<string> */
    protected $fillable = [
        self::CODE,
        self::NAME,
    ];

    /** @var array<string, string> */
    protected $casts = [
        self::CODE => 'integer',
        self::NAME => 'string',
    ];

    /**
     * لیست شهرهای این استان
     *
     * @return HasMany<City>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(
            related: City::class,
            foreignKey: City::PROVINCE_CODE,
            localKey: self::CODE
        );
    }
}
