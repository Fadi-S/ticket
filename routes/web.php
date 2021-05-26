<?php


use App\Http\Controllers\API\ReservationsController;
use App\Http\Controllers\EventsController;
use App\Http\Livewire\DuplicatesTable;
use App\Http\Livewire\Friends;
use App\Http\Livewire\MakeReservation;
use App\Http\Livewire\PeriodForm;
use App\Http\Livewire\PeriodsTable;
use App\Http\Livewire\ResetPasswordByPhone;
use App\Http\Livewire\TemplatesForm;
use App\Http\Livewire\Tickets;
use App\Http\Livewire\Users\UsersTable;
use App\Http\Livewire\VerifyPhoneNumber;
use App\Http\Middleware\EnsurePhoneNumberIsVerified;
use App\Http\Middleware\UnVerified;
use App\Models\User\User;
use App\Http\Controllers\Admin\{AuthController, DashboardController, UsersController};
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Livewire\Users\UserForm;
use Spatie\Honeypot\ProtectAgainstSpam;


Route::fallback(fn() => response()->view('errors.404', [], 404));

Route::middleware(ProtectAgainstSpam::class)
    ->group(function () {
        Route::get('password/forgot', fn() => view('auth.forgot'));
        Route::get('password/phone', ResetPasswordByPhone::class);

        Auth::routes(['verify' => true, 'register' => config('settings.allow_users_to_create_accounts')]);
    });

Route::get('/verify', VerifyPhoneNumber::class)
    ->middleware(['auth', UnVerified::class]);

Route::get('/admin', [DashboardController::class, 'adminHackerTrap']);
Route::get('/wp-admin', [DashboardController::class, 'adminHackerTrap']);

Route::get('lang/{locale}', function ($locale) {
    if(!in_array($locale, array_keys(app()->make('locales'))))
        abort(404);

    if(auth()->check()) {
        $user = auth()->user();
        $user->locale = $locale;
        $user->save();
    }

    setcookie('locale', $locale, time()+60*60*24*365*10, '/'); // For 10 years

    return back();
});

Route::middleware(["auth", EnsurePhoneNumberIsVerified::class])->group(function() {
    Route::get('/assets/{image}', function ($image) {
        $width = request('w') ?? 200;
        $height = request('h');

        return Image::make(public_path("/images/$image"))
            ->resize($width, $height, fn($constrains) => $constrains->aspectRatio())
            ->response();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('users/create', UserForm::class);
    Route::get('users/{user}/edit', UserForm::class);

    Route::resource("users", UsersController::class)
        ->except('create', 'edit', 'index');

    Route::get('/users', UsersTable::class);

    Route::get('/templates/create', TemplatesForm::class);
    Route::get('/templates/{template}/edit', TemplatesForm::class);

    Route::get('/periods/create', PeriodForm::class);
    Route::get('/periods/{period}/edit', PeriodForm::class);
    Route::get('/periods', PeriodsTable::class);

    Route::get("tickets", Tickets::class);

    Route::get('/reserve', MakeReservation::class);

    Route::get('/logs', [DashboardController::class, 'showLogs']);

    Route::get("ajax/reservation/users", [ReservationsController::class, 'getUsers']);
    Route::get("ajax/reservation/events", [ReservationsController::class, 'getEvents']);

    Route::get('/friends', Friends::class);

    Route::resource("{eventType}", EventsController::class)
        ->parameters(['{eventType}' => 'event', 'eventType' => 'eventType'])
        ->only(['create', 'edit', 'index', 'store', 'update', 'destroy']);
});
