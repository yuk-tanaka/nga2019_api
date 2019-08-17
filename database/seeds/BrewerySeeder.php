<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class BrewerySeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'breweries';
        $this->filename = database_path('seeds/csv/breweries.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'id',
            1 => 'name',
            2 => 'prefecture',
            3 => 'sakenote_maker_id',
            4 => 'company_name',
            5 => 'address',
            6 => 'web',
            7 => 'sake_shop_id',
        ];
    }
}
