<?php


use App\Http\Controllers\TicketsController;
use App\Http\Livewire\Friends;
use App\Http\Livewire\MakeReservation;
use App\Http\Livewire\ResetPasswordByPhone;
use App\Http\Controllers\Admin\{AuthController, DashboardController, KiahkController, MassesController, ReservationsController, UsersController, VespersController};
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Livewire\Users\UserForm;
use Spatie\Honeypot\ProtectAgainstSpam;


Route::fallback(fn() => response()->view('errors.404', [], 404));

Route::middleware(ProtectAgainstSpam::class)
    ->group(function () {
        Route::get('password/forgot', fn() => view('auth.forgot'));
        Route::get('password/phone', ResetPasswordByPhone::class);

        Auth::routes(['verify' => true]);
    });

Route::get('/assets/{image}', function ($image) {
    $width = request('w') ?? 200;
    $height = request('h');

    return Image::make(public_path("/images/$image"))
        ->resize($width, $height, fn($constrains) => $constrains->aspectRatio())
        ->response();
});

Route::middleware("auth")->group(function() {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('users/create', UserForm::class);
    Route::get('users/{user}/edit', UserForm::class);

    Route::resource("users", UsersController::class)
        ->except('create', 'edit');

    Route::resource("masses", MassesController::class);
    Route::resource("kiahk", KiahkController::class);
    Route::resource("vespers", VespersController::class);

    Route::resource("reservations", ReservationsController::class)
        ->except(['show', 'index']);

    Route::resource("tickets", TicketsController::class)
        ->only(['show', 'index', 'edit', 'update', 'destroy']);

    Route::get('/reserve', MakeReservation::class);

    Route::get('/logs', [DashboardController::class, 'showLogs']);

    Route::get("ajax/reservation/users", [\App\Http\Controllers\API\ReservationsController::class, 'getUsers']);
    Route::get("ajax/reservation/events", [\App\Http\Controllers\API\ReservationsController::class, 'getEvents']);

    Route::get('/friends', Friends::class);

    Route::get('lang', function () {
        $user = auth()->user();
        $user->locale = app()->currentLocale() === 'ar' ? 'en' : 'ar';
        Cookie::forever('locale', $user->locale);
        $user->save();

        return back();
    });
});
