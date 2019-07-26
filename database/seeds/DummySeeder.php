<?php

use App\Eloquents\Brewery;
use App\Eloquents\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /** @var Collection */
    private $opens = [
        '2019-10-01T12:00:00',
        '2019-10-01T12:30:00',
        '2019-10-01T13:00:00',
        '2019-10-01T13:30:00',
        '2019-10-01T14:00:00',
        '2019-10-01T14:15:00',
        '2019-10-01T14:30:00',
        '2019-10-01T14:45:00',
        '2019-10-01T15:00:00',
    ];

    /** @var Collection */
    private $closes = [
        '2019-10-01T19:00:00',
        '2019-10-01T19:30:00',
        '2019-10-01T20:00:00',
        '2019-10-01T20:30:00',
        '2019-10-01T21:00:00',
        '2019-10-01T21:15:00',
        '2019-10-01T21:30:00',
        '2019-10-01T21:45:00',
        '2019-10-01T22:00:00',
    ];

    /**
     * DummySeeder constructor.
     */
    public function __construct()
    {
        $this->opens = collect($this->opens);
        $this->closes = collect($this->closes);
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Restaurant::class, 100)
            ->create()
            ->each(function (Restaurant $restaurant) {
                $brewery = factory(Brewery::class)->create();
                //2019
                $brewery->restaurants()->attach($restaurant->id, [
                    'year' => 2019,
                    'opened_at' => Carbon::parse($this->opens->random()),
                    'closed_at' => Carbon::parse($this->opens->random()),
                ]);
                //2018
                if (mt_rand(0, 1)) {
                    $brewery->restaurants()->attach($restaurant->id, [
                        'year' => 2018,
                        'opened_at' => Carbon::parse($this->opens->random()),
                        'closed_at' => Carbon::parse($this->opens->random()),
                    ]);
                }
            });
    }
}