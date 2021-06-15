<?php

use App\Http\Controllers\API\UserAuthController;

Route::middleware('auth:api')->group(function () {

    Route::get('/user',
        fn() => auth()->user()
    );

    Route::get('/signed-in', fn() => ['success' => 'User is Signed in']);

});

Route::post('/login', [UserAuthController::class, 'login']);
