<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class RestaurantSeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'restaurants';
        $this->filename = database_path('seeds/csv/restaurants.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'id',
            1 => 'area_id',
            2 => 'name',
            3 => 'google_search_keyword',
            4 => 'google_place_id',
            5 => 'address',
            6 => 'latitude',
            7 => 'longitude',
            8 => 'tel',
            9 => 'web',
            10 => 'twitter',
            11 => 'facebook',
            12 => 'tabelog',
            13 => 'can_auto_update',
            14 => 'is_closed',
        ];
    }
}
