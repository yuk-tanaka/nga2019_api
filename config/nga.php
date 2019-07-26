<?php

return [
    //全年度取得したいときのURL
    'all_year_parameter' => 'all',
    //対象年度
    'years' => [2018, 2019],
    //api paginate
    'paginate' => 12,

    //API
    'google_api_key' => env('GOOGLE_API_KEY'),
    'sakenote_api_key' => env('SAKENOTE_API_KEY'),
    'sakenote_api_url' =>  env('SAKENOTE_API_URL'),
];
