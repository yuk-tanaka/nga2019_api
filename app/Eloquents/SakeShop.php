<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Eloquents\SakeShop
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string|null $google_place_id
 * @property string|null $google_url
 * @property string|null $address
 * @property string|null $tel
 * @property string|null $business_hours
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Brewery[] $brewery
 * @property-read \App\Eloquents\City $city
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereBusinessHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereGooglePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereGoogleUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\SakeShop whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SakeShop extends Model
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
        'google_place_id',
        'name',
        'google_url',
        'address',
        'tel',
        'business_hours',
    ];

    /**
     * nullable
     * @return HasMany
     */
    public function brewery(): HasMany
    {
        return $this->hasMany(Brewery::class);
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
