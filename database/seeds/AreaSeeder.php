<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class AreaSeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'areas';
        $this->filename = database_path('seeds/csv/areas.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'id',
            1 => 'city_id',
            2 => 'name',
            3 => 'color',
        ];
    }
}