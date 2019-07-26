<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Participant;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchParticipantForAreasRequest;
use App\Http\Requests\SearchParticipantForDistanceRequest;
use App\Http\Requests\SearchParticipantForFavoritesRequest;
use App\Http\Resources\TimelineResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParticipantController extends Controller
{
    /**
     * @var Participant
     */
    private $participant;

    /**
     * ParticipantController constructor.
     * @param Participant $participant
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant->with($this->with());
    }

    /**
     * @param int $year
     * @param SearchParticipantForAreasRequest $request
     * @return LengthAwarePaginator
     */
    public function index(int $year, SearchParticipantForAreasRequest $request): LengthAwarePaginator
    {
        return $this->participant
            ->year($year)
            ->areas($request->areaIds)
            ->orderBy('restaurant_id', 'asc')
            ->paginate(config('nga.paginate'))
            ->appends($request->all());
    }

    /**
     * @param int $year
     * @return LengthAwarePaginator
     */
    public function all(int $year): LengthAwarePaginator
    {
        return $this->participant->year($year)->orderBy('restaurant_id', 'asc')->paginate(config('nga.paginate'));
    }

    /**
     * @param int $year
     * @param SearchParticipantForFavoritesRequest $request
     * @return LengthAwarePaginator
     */
    public function favorites(int $year, SearchParticipantForFavoritesRequest $request): LengthAwarePaginator
    {
        return $this->participant
            ->year($year)
            ->whereIn('id', $request->participantIds)
            ->orderBy('restaurant_id', 'asc')
            ->paginate(config('nga.paginate'))
            ->appends($request->all());
    }

    /**
     * @param int $year
     * @param SearchParticipantForDistanceRequest $request
     * @return LengthAwarePaginator
     */
    public function nearby(int $year, SearchParticipantForDistanceRequest $request): LengthAwarePaginator
    {
        return $this->participant
            ->year($year)
            ->nearby($request->latitude, $request->longitude)
            ->paginate(config('nga.paginate'))
            ->appends($request->all());
    }

    /**
     * @param int $year
     * @param SearchParticipantForAreasRequest $request
     * @return ResourceCollection
     */
    public function timeline(int $year, SearchParticipantForAreasRequest $request): ResourceCollection
    {
        $participants = $this->participant
            ->year($year)
            ->areas($request->areaIds)
            ->orderBy('opened_at', 'asc')
            ->get();

        return TimelineResource::collection($participants);
    }

    /**
     * @param Participant $participant
     * @return Participant
     */
    public function show(Participant $participant): Participant
    {
        return $participant->load($this->with());
    }

    /**
     * @return array
     */
    private function with(): array
    {
        return [
            'brewery',
            'restaurant',
            'restaurant.area',
            'restaurant.area.city',
        ];
    }
}
