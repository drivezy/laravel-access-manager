<?php
Route::group(['namespace' => 'Drivezy\LaravelAccessManager\Controllers'], function () {

    Route::get('getUserSessionDetails', 'LoginController@getSessionDetails');
});


