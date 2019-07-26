<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Eloquents\Area;
use App\Eloquents\Restaurant;
use Faker\Generator as Faker;

$factory->define(Restaurant::class, function (Faker $faker) {
    return [
        'area_id' => $faker->numberBetween(1, Area::query()->count()),
        'name' => $faker->word,
        'address' => $faker->address,
        'latitude' => $faker->latitude(35, 40),
        'longitude' => $faker->longitude(135, 145),
        'tel' => $faker->boolean ? $faker->phoneNumber : null,
        'description' => $faker->realText(50),
        'web' => $faker->boolean ? $faker->url : null,
        'twitter' => $faker->boolean ? $faker->url : null,
        'facebook' => $faker->boolean ? $faker->url : null,
        'tabelog' => $faker->boolean ? $faker->url : null,
    ];
});
