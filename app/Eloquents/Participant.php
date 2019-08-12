<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Eloquents\Participant
 *
 * @property int $id
 * @property int $restaurant_id
 * @property int $brewery_id
 * @property int $year
 * @property string $opened_at
 * @property string $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereBreweryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereYear($value)
 * @mixin \Eloquent
 * @property-read \App\Eloquents\Brewery $brewery
 * @property-read string $business_hours
 * @property-read \App\Eloquents\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant areas($areaIds)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant year($year)
 * @property \Illuminate\Support\Carbon|null $opened_at_after_break
 * @property \Illuminate\Support\Carbon|null $closed_at_after_break
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant nearby($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereClosedAtAfterBreak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Participant whereOpenedAtAfterBreak($value)
 */
class Participant extends Model
{
    use CommonScopes;

    /** @var string */
    private const BUSINESS_HOUR_DELIMITER = '-';

    /** @var array */
    protected $appends = [
        'business_hours',
    ];

    /** @var array */
    protected $casts = [
        'shop_id' => 'integer',
    ];

    /** @var array */
    protected $dates = [
        'opened_at',
        'closed_at',
        'opened_at_after_break',
        'closed_at_after_break',
        'created_at',
        'updated_at',
    ];

    /** @var array */
    protected $fillable = [
        'brewery_id',
        'restaurant_id',
        'year',
        'opened_at',
        'closed_at',
        'opened_at_after_break',
        'closed_at_after_break',
        'restaurant_description',
        'sake_description',
    ];

    /**
     * @return BelongsTo
     */
    public function brewery(): BelongsTo
    {
        return $this->belongsTo(Brewery::class);
    }

    /**
     * @return BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @param Builder $builder
     * @param array $areaIds
     * @return Builder
     */
    public function scopeAreas(Builder $builder, array $areaIds): Builder
    {
        return $builder->whereHas('restaurant', function (Builder $builder) use ($areaIds) {
            $builder->whereIn('area_id', $areaIds);
        });
    }

    /**
     * @param Builder $builder
     * @param int $year
     * @return Builder
     */
    public function scopeYear(Builder $builder, int $year): Builder
    {
        return $builder->where('year', $year);
    }

    /**
     * 距離近い店を計算するSQL
     * 参考：https://qiita.com/yangci/items/dffaacf424ebeb1dd643
     * @param Builder $builder
     * @param float $latitude
     * @param float $longitude
     * @return Builder
     */
    public function scopeNearby(Builder $builder, float $latitude, float $longitude): Builder
    {
        $select = <<<SQL
  (
    6371 * 1000 * acos(
      cos(radians({$latitude}))
      * cos(radians(latitude))
      * cos(radians(longitude) - radians({$longitude}))
      + sin(radians({$latitude}))
      * sin(radians(latitude))
    )
  ) AS distance
SQL;
        return $builder
            ->join('restaurants', 'restaurants.id', '=', 'restaurant_id')
            ->where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->select($this->fillable)
            ->selectRaw($select)
            ->orderBy('distance', 'asc');
    }

    /**
     * 営業時間
     * @return string
     */
    public function getBusinessHoursAttribute(): string
    {
        $hour = $this->opened_at->format('H:i')
            . self::BUSINESS_HOUR_DELIMITER
            . $this->closed_at->format('H:i');

        if ($this->opened_at_after_break && $this->closed_at_after_break) {
            $hour .= "&nbsp;"
                . $this->opened_at_after_break->format('H:i')
                . self::BUSINESS_HOUR_DELIMITER
                . $this->closed_at_after_break->format('H:i');
        }

        return $hour;
    }
}
