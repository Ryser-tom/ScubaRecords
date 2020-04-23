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
    return $request->user();
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

Route::get('cert', 'CertificationController@index');
Route::get('cert.last/{user}', 'CertificationController@last');
Route::post('cert', 'CertificationController@store');
Route::delete('cert/{cert}', 'CertificationController@delete');