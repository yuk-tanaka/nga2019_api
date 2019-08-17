<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class SakeShopSeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'sake_shops';
        $this->filename = database_path('seeds/csv/sake_shops.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'id',
            1 => 'city_id',
            2 => 'google_place_id',
            3 => 'name',
        ];
    }
}
