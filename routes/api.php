<?php

use App\Http\Controllers\API\UserAuthController;

Route::middleware('auth:api')->group(function () {

    Route::get('/user',
        fn() => auth()->user()
    );



});

Route::post('/login', [UserAuthController::class, 'login']);
