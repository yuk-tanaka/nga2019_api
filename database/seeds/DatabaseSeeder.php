<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreaSeeder::class);
        $this->call(BrewerySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(ParticipantSeeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(SakeShopSeeder::class);
    }
}
