<?php

Route::get('/login', 'Admin\AuthController@showLoginForm')->name("login");
Route::post('/login', 'Admin\AuthController@login');

Route::middleware("auth")->group(function() {

    Route::get('/logout', 'Admin\AuthController@logout');

    Route::get('/', 'Admin\DashboardController@index');
    Route::resource("users", 'Admin\UsersController');
    Route::resource("admins", 'Admin\AdminsController');

});

