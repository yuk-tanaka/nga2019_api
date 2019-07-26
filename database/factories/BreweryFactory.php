<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Eloquents\Brewery;
use Faker\Generator as Faker;

$factory->define(Brewery::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'prefecture' => $faker->prefecture,
        'web' => $faker->boolean ? $faker->url : null,
        'twitter' => $faker->boolean ? $faker->url : null,
        'facebook' => $faker->boolean ? $faker->url : null,
    ];
});
