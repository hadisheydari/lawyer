<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name نام بیمه
 * @property int $code کد بیمه
 * @property float|null $coefficient ضریب بیمه
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
class Insurance extends Model
{
    protected $fillable = [
        'name',
        'code',
        'coefficient',
    ];
}
