<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//city
Route::get('cities', 'CityController@all');
Route::get('cities/areas', 'CityController@allAreasFromMultipleCities');
Route::get('{year}/cities', 'CityController@index');
Route::get('{year}/cities/areas', 'CityController@areasFromMultipleCities');
Route::get('{year}/cities/{city}', 'CityController@areas');

//participant
Route::get('{year}/participants', 'ParticipantController@index');
Route::get('{year}/participants/all', 'ParticipantController@all');
Route::get('{year}/participants/timeline', 'ParticipantController@timeline');
Route::get('{year}/participants/favorites', 'ParticipantController@favorites');
Route::get('{year}/participants/nearby', 'ParticipantController@nearby');
Route::get('{year}/participants/{participant}', 'ParticipantController@show');

//restaurant
Route::get('/restaurants', 'RestaurantController@index');
Route::get('/restaurants/all', 'RestaurantController@all');


