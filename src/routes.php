<?php
Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers'], function () {

    Route::get('getSessionDetails', 'LoginController@getSessionDetails');
});


