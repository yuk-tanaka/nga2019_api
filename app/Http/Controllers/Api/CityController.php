<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Area;
use App\Eloquents\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchAreasFromMultipleCitiesRequest;
use Illuminate\Database\Eloquent\Collection;

class CityController extends Controller
{
    /**
     * @var Area
     */
    private $area;

    /**
     * @var City
     */
    private $city;

    /**
     * CityController constructor.
     * @param Area $area
     * @param City $city
     */
    public function __construct(Area $area, City $city)
    {
        $this->area = $area;
        $this->city = $city;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->city->asc()->get();
    }

    /**
     * @param FetchAreasFromMultipleCitiesRequest $request
     * @return Collection
     */
    public function allAreasFromMultipleCities(FetchAreasFromMultipleCitiesRequest $request): Collection
    {
        return $this->area
            ->whereIn('city_id', $request->cityIds)
            ->orderBy('city_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param int|string $year
     * @return Collection
     */
    public function index($year): Collection
    {
        return $this->city->year($year)->asc()->get();
    }

    /**
     * @param int|string $year
     * @param FetchAreasFromMultipleCitiesRequest $request
     * @return Collection
     */
    public function areasFromMultipleCities($year, FetchAreasFromMultipleCitiesRequest $request): Collection
    {
        return $this->area
            ->whereIn('city_id', $request->cityIds)
            ->year($year)
            ->orderBy('city_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param int|string $year
     * @param City $city
     * @return Collection
     */
    public function areas($year, City $city): Collection
    {
        return $city->areas()->year($year)->asc()->get();
    }
}
