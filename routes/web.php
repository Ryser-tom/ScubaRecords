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
Route::view('/test', 'diveUpdate');
Route::view('/importDive', 'importDives');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/user/{username}', 'UserController@show');
Route::get('/club/{clubId}', 'ClubController@show');
Route::get('/clubs/all', 'ClubController@index');
Route::get('/clubs/my', 'ClubController@userClubs');

Route::get('/dives/public', 'DiveController@index');
Route::get('/dives/public/{site}', 'DiveController@publicSite');
Route::get('/dives/personnal', 'DiveController@personnal');
Route::get('/dives/personnal/{site}', 'DiveController@personnalSite');
Route::get('/dives/followed', 'DiveController@followed');

Route::get('/diveSites/public', 'DiveSiteController@public');
Route::get('/diveSites/personnal', 'DiveSiteController@personnal');
Route::get('/diveSites/followed', 'DiveSiteController@followed');

Route::get('/dive/{diveId}', 'DiveController@show')->name('showDive');
Route::get('/dive/update/{diveId}', 'DiveController@showUpdate');
Route::post('/uploadDive', 'DiveController@store');
Route::post('/sendUpdate', 'DiveController@update');

Route::post('/follow', 'UserController@follow');