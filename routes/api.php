<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
});

/*
|--------------------------------------------------------------------------
| Club Routes
|--------------------------------------------------------------------------
*/

Route::get('club', 'ClubController@index');
Route::get('club/{club}', 'ClubController@show');
Route::post('club', 'ClubController@store');
Route::put('club/{club}', 'ClubController@update');
Route::delete('club/{club}', 'ClubController@delete');
Route::get('members/{club}', 'ClubController@getMembers');
Route::post('members', 'ClubController@addMember');
Route::delete('members/{user}/{club}', 'ClubController@deleteMember');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::get('users', 'UserController@index');
//Route::get('users/{user}', 'UserController@show');
//Route::post('users', 'UserController@store');
//Route::put('users/{user}', 'UserController@update');
//Route::delete('users/{user}', 'UserController@delete');


/*
|--------------------------------------------------------------------------
| Certification Routes
| not finished
|--------------------------------------------------------------------------
*/

Route::get('cert', 'CertificationController@index');
Route::middleware('auth:api')->get('cert/{user}', 'CertificationController@lastOfUser');
Route::post('cert', 'CertificationController@store');
Route::delete('cert/{cert}', 'CertificationController@delete');

/*
|--------------------------------------------------------------------------
| Event Routes
| not tested
|--------------------------------------------------------------------------
*/

Route::get('event', 'EventController@index');
Route::get('event/{event}', 'EventController@show');
Route::post('event', 'EventController@store');
Route::put('event/{event}', 'EventController@update');
Route::delete('event/{event}', 'EventController@delete');

/*
|--------------------------------------------------------------------------
| Dive Routes
|--------------------------------------------------------------------------
*/

Route::get('dives', 'DiveController@index');
Route::get('dives/{dive}', 'DiveController@show');
Route::post('dives', 'DiveController@store');
//Route::put('dives/{user}', 'DiveController@update');
//Route::delete('dives/{user}', 'DiveController@delete');

Route::get('dives/test', 'DiveController@test');