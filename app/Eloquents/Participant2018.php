<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Eloquents\Participant2018
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $restaurant_id
 * @property int $brewery_id
 * @property int $year
 * @property \Illuminate\Support\Carbon $opened_at
 * @property \Illuminate\Support\Carbon $closed_at
 * @property-read \App\Eloquents\Brewery $brewery
 * @property-read string $business_hours
 * @property-read \App\Eloquents\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant areas($areaIds)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereBreweryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant year($year)
 * @property \Illuminate\Support\Carbon|null $opened_at_after_break
 * @property \Illuminate\Support\Carbon|null $closed_at_after_break
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant nearby($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereClosedAtAfterBreak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant2018 whereOpenedAtAfterBreak($value)
 */
class Participant2018 extends Participant
{
    /** @var string  */
    protected $table = 'participants';

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('year', function (Builder $builder) {
            $builder->where('year', 2018);
        });
    }
}
