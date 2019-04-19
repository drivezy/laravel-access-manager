<?php
Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers'], function () {

    Route::get('getUserSessionDetails', 'LoginController@getUserSessionDetails');
});

Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers',
              'prefix'    => 'api/record'], function () {
    
    Route::resource('ipRestriction', 'IPRestrictionController');
});

