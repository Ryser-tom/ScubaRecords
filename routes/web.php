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
//TODO: all clubs
Route::get('/', function () {
    return view('/home');
});

Route::view('/welcome', 'welcome');
Route::view('/test', 'clubUpdate');
Route::view('/importDive', 'importDives');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/user/update', 'UserController@showUpdate');
Route::get('/user/{username}', 'UserController@show')->name('showUser');;

Route::get('/club/{clubId}', 'ClubController@show');//TODO: check if club exist
Route::get('/clubs/all', 'ClubController@index');
Route::get('/clubs/my', 'ClubController@userClubs');
Route::get('/clubs/update/{clubName}', 'ClubController@showUpdate')->name('showClub');
Route::get('/closeClub/{idClub}', 'ClubController@close');

Route::get('/dives/public', 'DiveController@index');
Route::get('/dives/public/{site}', 'DiveController@publicSite')->name('showSite');
Route::get('/dives/personnal', 'DiveController@personnal');
Route::get('/dives/personnal/{site}', 'DiveController@personnalSite');
Route::get('/dives/followed', 'DiveController@followed');
Route::get('/dive/{diveId}', 'DiveController@show')->name('showDive');
Route::get('/dive/update/{diveId}', 'DiveController@showUpdate')->name('showUpdateDive');

Route::get('/diveSites/public', 'DiveSiteController@public');
Route::get('/diveSites/personnal', 'DiveSiteController@personnal');
Route::get('/diveSites/followed', 'DiveSiteController@followed');

Route::post('/follow', 'UserController@follow');
Route::post('/uploadDive', 'DiveController@store');
Route::post('/membership', 'ClubController@changeMembership');
Route::post('/sendUpdateDive', 'DiveController@update');
Route::post('/sendUpdateClub', 'ClubController@update');
Route::post('/sendUpdateUser', 'UserController@update');
Route::post('/sendUpdateSite', 'DiveSiteController@update');

//TODO: change name in form
Route::post('/Site/update', function () {

    $dataUpdate["longitude"] = $_POST["lng"];
    $dataUpdate["latitude"] = $_POST["lat"];
    $dataUpdate["name"] = $_POST["name"];
    $dataUpdate["description"] = $_POST["description"];
    $dataUpdate["difficulty"] = $_POST["difficulty"];

    return view('siteUpdate')->with( 'data', $dataUpdate);
});