<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\{AuthController,
    DashboardController,
    KiahkController,
    MassesController,
    ReservationsController,
    UsersController};
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Livewire\Users\UserForm;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::middleware(ProtectAgainstSpam::class)->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name("login");
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name("register");
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::get('/assets/{image}', function ($image) {
    $width = request('w');
    $height = request('h');

    return Image::make(public_path("/images/$image"))
        ->resize($width, $height, fn($constrains) => $constrains->aspectRatio())
        ->response();
});

Route::middleware("auth")->group(function() {

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('users/create', UserForm::class);
    Route::get('users/{user}/edit', UserForm::class);

    Route::resource("users", UsersController::class)
        ->except('create', 'edit');

    Route::resource("masses", MassesController::class);
    Route::resource("kiahk", KiahkController::class);

    Route::resource("reservations", ReservationsController::class);

    Route::get('/logs', [DashboardController::class, 'showLogs']);
});
