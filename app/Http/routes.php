<?php

use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'MainController@index');
Route::get('/data', 'MainController@data');
Route::get('/weeks', 'MainController@weeks');
Route::get('/months', 'MainController@months');

Route::get('/slider/range', 'SliderController@range');
