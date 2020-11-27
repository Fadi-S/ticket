<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\{
    AuthController,
    DashboardController,
    MassesController,
    ReservationsController,
    UsersController
};
use App\Http\Livewire\Users\UserForm;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name("login");
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name("register");
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware("auth")->group(function() {

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('users/create', UserForm::class);
    Route::get('users/{user}/edit', UserForm::class);

    Route::resource("users", UsersController::class)
        ->except('create', 'edit');

    Route::resource("masses", MassesController::class);
    Route::resource("reservations", ReservationsController::class);

    Route::get('/logs', [DashboardController::class, 'showLogs']);

});
