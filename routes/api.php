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

Route::get('/id/{data}', 'receipeController@showId');
Route::get('/cuisine/{data}', 'receipeController@showCuisine');
Route::get('/cuisine/{data}/{page}', 'receipeController@showCuisine');
Route::put('/update','receipeController@update');
Route::post('/create','receipeController@create');
