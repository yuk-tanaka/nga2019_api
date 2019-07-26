<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Eloquents\City
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Area[] $areas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Restaurant[] $restaurants
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City whereName($value)
 * @mixin \Eloquent
 * @property int $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\City year($year)
 */
class City extends Model
{
    use CommonScopes;

    /** @var array */
    protected $fillable = [
        'id',
        'name',
        'order',
    ];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    /**
     * @return HasManyThrough
     */
    public function restaurants(): HasManyThrough
    {
        return $this->hasManyThrough(Restaurant::class, Area::class);
    }

    /**
     * @param Builder $builder
     * @param int|string $year
     * @return Builder
     */
    public function scopeYear(Builder $builder, $year): Builder
    {
        if ($year === config('nga.all_year_parameter')) {
            return $builder;
        }

        if (!collect(config('nga.years'))->contains($year)) {
            abort(404);
        }

        return $builder->whereHas('areas', function (Builder $builder) use ($year) {
            $builder->year($year);
        });
    }
}
