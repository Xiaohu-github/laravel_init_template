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

Route::namespace('Api')->prefix('v1')->group(function () {

    Route::get('/index','UserController@index')->name('users.index');
    Route::post('/signup','UserController@signup')->name('users.signup');
    Route::post('/login','UserController@login')->name('user.login');
    Route::get('/info','UserController@info')->name('user.info');
    Route::get('/logout','UserController@logout')->name('user.logout');
});
