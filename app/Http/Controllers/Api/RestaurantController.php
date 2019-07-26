<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Restaurant;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRestaurantForAreasRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class RestaurantController extends Controller
{
    /**
     * @var Restaurant
     */
    private $restaurant;

    /**
     * RestaurantController constructor.
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant->with([
            'area',
            'area.city',
            'participants',
        ]);
    }

    /**
     * @param SearchRestaurantForAreasRequest $request
     * @return LengthAwarePaginator
     */
    public function index(SearchRestaurantForAreasRequest $request): LengthAwarePaginator
    {
        return $this->restaurant
            ->areas($request->areaIds)
            ->asc()
            ->paginate(config('nga.paginate'))
            ->appends($request->all());
    }

    /**
     * @return LengthAwarePaginator
     */
    public function all(): LengthAwarePaginator
    {
        return $this->restaurant->asc()->paginate(config('nga.paginate'));
    }
}
