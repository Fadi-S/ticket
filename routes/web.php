<?php

use App\Http\Controllers\TicketsController;
use App\Http\Controllers\Admin\{AuthController,
    DashboardController,
    KiahkController,
    MassesController,
    ReservationsController,
    UsersController};
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Livewire\Users\UserForm;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::middleware(ProtectAgainstSpam::class)
    ->group(fn() => Auth::routes(['verify' => true]));

Route::get('/assets/{image}', function ($image) {
    $width = request('w') ?? 200;
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

    Route::resource("reservations", ReservationsController::class)
        ->except(['show', 'index']);

    Route::resource("tickets", TicketsController::class)
        ->only(['show', 'index', 'edit', 'update', 'destroy']);

    Route::get('/logs', [DashboardController::class, 'showLogs']);
});
