<?php

use App\Http\Controllers\API\{EventsController, UserAuthController};

Route::middleware('auth:api')->group(function () {

    Route::get('/user', fn() => auth()->user()->toAPI());

    Route::get('/signed-in', fn() => ['success' => 'User is Signed in']);

    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::get('events', [EventsController::class, 'index']);

    Route::get('events/reserved', [EventsController::class, 'reserved']);
});

Route::post('/login', [UserAuthController::class, 'login']);
