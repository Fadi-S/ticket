<?php

use App\Http\Controllers\API\ReservationsController;
use App\Http\Controllers\API\UsersController;

Route::middleware('auth:api')->get('/user', fn(Request $request) => $request->user());

Route::get("/reservation/users", [ReservationsController::class, 'getUsers']);
Route::get("/reservation/events", [ReservationsController::class, 'getEvents']);
