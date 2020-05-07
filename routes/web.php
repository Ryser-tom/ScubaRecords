<?php

/*******************************************************************************
AUTEUR      : Tom Ryser
LIEU        : CFPT Informatique Genève
DATE        : Avril 2020
TITRE PROJET: ScubaRecords
VERSION     : 1.0
*******************************************************************************/

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('/home');
});

Route::view('/welcome', 'welcome');
Route::view('/test', 'importDives');
Route::view('/importDive', 'importDives');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/user/{username}', 'UserController@show');
Route::get('/club/{clubId}', 'ClubController@show');

Route::get('/dives/public', 'DiveController@index');
Route::get('/dives/personnal', 'DiveController@personnal');
Route::get('/dives/follow', 'DiveController@follow');

Route::get('/dive/{diveId}', 'DiveController@show');
Route::post('/uploadDive', 'DiveController@store');