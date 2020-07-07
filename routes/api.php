<?php

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/username/generate", 'API\UsersController@generateUsername');
Route::post("/username/check", 'API\UsersController@checkUsername');
