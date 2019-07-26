<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Eloquents\Area
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $color
 * @property-read \App\Eloquents\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Restaurant[] $restaurants
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area whereName($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Area year($year)
 */
class Area extends Model
{
    use CommonScopes;

    /** @var array */
    protected $casts = [
        'city_id' => 'integer',
    ];

    /** @var array */
    protected $fillable = [
        'id',
        'city_id',
        'name',
        'color',
    ];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return HasMany
     */
    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
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

        return $builder->whereHas('restaurants', function (Builder $builder) use ($year) {
            $builder->has('participant' . $year);
        });
    }
}
