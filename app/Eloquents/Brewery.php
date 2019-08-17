<?php

namespace App\Eloquents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * App\Eloquents\Brewery
 *
 * @property int $id
 * @property string $name
 * @property string $prefecture
 * @property string|null $web
 * @property string|null $twitter
 * @property string|null $facebook
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquents\Restaurant[] $restaurants
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery asc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery desc()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery wherePrefecture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereWeb($value)
 * @mixin \Eloquent
 * @property string|null $sakenote_maker_id
 * @property string|null $company_name
 * @property string|null $address
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereSakenoteMakerId($value)
 * @property int|null $sake_shop_id
 * @property-read \App\Eloquents\SakeShop|null $sakeShop
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Eloquents\Brewery whereSakeShopId($value)
 */
class Brewery extends Model
{
    use CommonScopes;

    /** @var array */
    protected $casts = [
        'restaurant_id' => 'integer',
        'sake_shop_id' => 'integer',
    ];

    /** @var array */
    protected $fillable = [
        'restaurant_id',
        'sake_shop_id',
        'name',
        'prefecture',
        'sakenote_maker_id',
        'company_name',
        'address',
        'web',
        'twitter',
        'facebook',
    ];

    /**
     * @return BelongsToMany
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, Participant::class);
    }

    /**
     * @return HasOneThrough
     */
    public function restaurant2019(): HasOneThrough
    {
        return $this->hasOneThrough(Restaurant::class, Participant2019::class);
    }

    /**
     * @return HasOneThrough
     */
    public function restaurant2018(): HasOneThrough
    {
        return $this->hasOneThrough(Restaurant::class, Participant2018::class);
    }

    /**
     * nullable
     * @return BelongsTo
     */
    public function sakeShop(): BelongsTo
    {
        return $this->belongsTo(SakeShop::class)->withDefault();
    }
}
