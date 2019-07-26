<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class CitySeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'cities';
        $this->filename = database_path('seeds/csv/cities.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'id',
            1 => 'name',
            2 => 'order',
        ];
    }
}