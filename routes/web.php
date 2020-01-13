<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//vue route...
Route::get('/', function () {

	return view('weather-vue');});

//laravel route...
Route::get('/weather', ['as' => 'weather', 'uses' => 'WeatherController@forecast']);
Route::get('/weather1', ['as' => 'weather', 'uses' => 'Weather1Controller@forecast']);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//accept any route for VueJS SPA functionality...
Route::get('/{any?}', function () {
	return view('component-vue');
})->where('any', '.*');
