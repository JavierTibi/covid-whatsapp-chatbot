<?php

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'auth:api'], function() {

        //Authentication
        Route::get('logout', 'AuthController@logout');

        //User
        Route::get('user', 'UserController@user');

        //Notification
        Route::post('notification/send', 'NotificationController@send');
        Route::get('notification/get', 'NotificationController@get');
        Route::get('notification/get/{id}', 'NotificationController@getByNotifiableId');
        Route::get('notification/all', 'NotificationController@getAll');
        Route::put('notification/status/{id}', 'NotificationController@updateStatus');
        Route::delete('notification/delete/{id}', 'NotificationController@delete');

        //API COVID
        Route::get('covid/{country}', 'CovidBotController@getByCountry');

    });
});

Route::group(['prefix' => 'covid'], function () {
    Route::post('country', 'CovidBotController@countryCasesSummary');
});
