<?php

Route::group(['middleware' => 'auth:api'], function () {

    //Auth and Token Routes
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');

    //Passwords Routes
    Route::get('/passwords','PasswordController@index');
    Route::post('/passwords/add','PasswordController@store');
    Route::put('/passwords/edit/{id}','PasswordController@update');
    Route::delete('/passwords/delete/{id}','PasswordController@destroy');
});


Route::post('/register','AuthController@register');
Route::post('/login', 'AuthController@login')->name('login');