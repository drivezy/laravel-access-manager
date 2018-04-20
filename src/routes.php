<?php

Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers',
              'prefix'    => 'access'], function () {
    Route::post('login', 'LoginController@login');
    Route::get('login', 'LoginController@loginCheck');
});


