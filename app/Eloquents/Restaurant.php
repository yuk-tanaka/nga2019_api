<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * App\Eloquents\Restaurant
 *
 * @property int $id
 * @property int $area_id
 * @property string $name
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property string|null $tel
 * @property string|null $description
 * @property string|null $web
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $tabelog
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Eloquents\Area $area
 * @property-read string $business_hours
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereTabelog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereWeb($value)
 * @mixin \Eloquent
 * @property string|null $google_search_keyword
 * @property string|null $google_place_id
 * @property string|null $google_url
 * @property bool $can_auto_update
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Brewery[] $breweries
 * @property-read \App\Eloquents\Participant2018 $participant2018
 * @property-read \App\Eloquents\Participant2019 $participant2019
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Participant[] $participants
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant areas($areaIds)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereBusinessHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereCanAutoUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereGooglePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereGoogleSearchKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereGoogleUrl($value)
 * @property bool $is_closed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Restaurant whereIsClosed($value)
 */
class Restaurant extends Model
{
    use CommonScopes;

    /** @var array */
    protected $casts = [
        'area_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'business_hours' => 'json',
        'can_auto_update' => 'boolean',
        'is_closed' => 'boolean',
    ];

    /** @var array */
    protected $fillable = [
        'id',
        'area_id',
        'name',
        'google_search_keyword',
        'google_place_id',
        'google_url',
        'address',
        'latitude',
        'longitude',
        'tel',
        'web',
        'twitter',
        'facebook',
        'tabelog',
        'business_hours',
        'can_auto_update',
        'is_closed',
    ];

    /**
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return BelongsToMany
     */
    public function breweries(): BelongsToMany
    {
        return $this->belongsToMany(Brewery::class, Participant::class);
    }

    /**
     * @return HasOneThrough
     */
    public function brewery2019(): HasOneThrough
    {
        return $this->hasOneThrough(Brewery::class, Participant2019::class);
    }

    /**
     * @return HasOneThrough
     */
    public function brewery2018(): HasOneThrough
    {
        return $this->hasOneThrough(Brewery::class, Participant2018::class);
    }

    /**
     * @return HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * @return HasOne
     */
    public function participant2019(): HasOne
    {
        return $this->hasOne(Participant2019::class);
    }

    /**
     * @return HasOne
     */
    public function participant2018(): HasOne
    {
        return $this->hasOne(Participant2018::class);
    }

    /**
     * @param Builder $builder
     * @param array $areaIds
     * @return Builder
     */
    public function scopeAreas(Builder $builder, array $areaIds): Builder
    {
        return $builder->whereIn('area_id', $areaIds);
    }
}
