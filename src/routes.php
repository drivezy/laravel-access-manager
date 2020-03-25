<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers'], function () {
    Route::get('getUserSessionDetails', 'LoginController@getUserSessionDetails');
    Route::post('login', 'LoginController@validatePasswordLogin');
    Route::post('user', 'UserController@store');
});

Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers',
              'prefix'    => 'api/record'], function () {

    Route::resource('ipRestriction', 'IPRestrictionController');
    Route::resource('user', 'UserController');
});

Route::group(['prefix'    => 'social',
              'namespace' => 'Drivezy\LaravelAccessManager\Controllers'], function () {
    Route::post('loginViaToken', 'SocialLoginController@loginViaToken');
    Route::get('callback/{provider}', 'SocialLoginController@validateCallback');
    Route::get('login/{provider}', 'SocialLoginController@login');
});
