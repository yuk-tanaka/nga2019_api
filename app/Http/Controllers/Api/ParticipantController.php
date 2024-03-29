<?php

namespace App\Http\Controllers\Api;

use App\Eloquents\Participant;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchTimelineRequest;
use App\Http\Requests\SearchParticipantForAreasRequest;
use App\Http\Requests\SearchParticipantForDistanceRequest;
use App\Http\Requests\SearchParticipantForFavoritesRequest;
use App\Http\Resources\TimelineResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            ->sort()
            ->paginate(config('nga.paginate'))
            ->appends($request->all());
    }

    /**
     * @param int $year
     * @return LengthAwarePaginator
     */
    public function all(int $year): LengthAwarePaginator
    {
        return $this->participant->year($year)->sort()->paginate(config('nga.paginate'));
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
            ->sort()
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
     * @param FetchTimelineRequest $request
     * @return ResourceCollection
     */
    public function timeline(int $year, FetchTimelineRequest $request): ResourceCollection
    {
        $builder = $request->areaIds
            ? $this->participant->year($year)->areas($request->areaIds)
            : $this->participant->whereIn('id', $request->participantIds);

        $participants = $builder->orderBy('opened_at', 'asc')->get();

        return TimelineResource::collection($participants);
    }

    /**
     * @param int $year
     * @param Participant $participant
     * @return Participant
     * @throws HttpException
     */
    public function show(int $year, Participant $participant): Participant
    {
        if ($participant->year !== $year) {
            abort(404);
        }

        return $participant->load($this->with());
    }

    /**
     * @return array
     */
    private function with(): array
    {
        return [
            'brewery',
            'brewery.sakeShop',
            'restaurant',
            'restaurant.area',
            'restaurant.area.city',
        ];
    }
}
