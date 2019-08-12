<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class ParticipantSeeder extends CsvSeeder
{
    /**
     * ConvertUserTableSeeder constructor.
     */
    public function __construct()
    {
        $this->table = 'participants';
        $this->filename = database_path('seeds/csv/participants.csv');
        $this->offset_rows = 1;
        $this->should_trim = true;

        $this->mapping = [
            0 => 'restaurant_id',
            1 => 'brewery_id',
            2 => 'year',
            3 => 'opened_at',
            4 => 'closed_at',
            5 => 'opened_at_after_break',
            6 => 'closed_at_after_break',
            7 => 'restaurant_description',
            8 => 'sake_description',
        ];
    }
}
