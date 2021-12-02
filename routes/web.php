<?php


use App\Http\Controllers\AmazonController;
use App\Http\Controllers\API\ReservationsController;
use App\Http\Controllers\EventsController;
use App\Http\Livewire\{AnnouncementForm,
    ConditionForm,
    Friends,
    MakeReservation,
    PeriodForm,
    PeriodsTable,
    ResetPasswordByPhone,
    TemplatesForm,
    Tickets,
    TypeConditions,
    TypesForm,
    Users\UsersTable,
    VerifyPhoneNumber,
    TypesTable,
    Users\UserForm};
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\EnsurePhoneNumberIsVerified;
use App\Http\Middleware\UnVerified;
use App\Http\Controllers\Admin\{AuthController, DashboardController, UsersController};
use Intervention\Image\ImageManagerStatic as Image;
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

Route::get('lang/{locale}', [LocaleController::class, 'index']);

Route::post('aws/bounce', [AmazonController::class, 'handle']);

Route::get('/disabled', fn() => view('disabled'))
    ->middleware('auth')
    ->name('disabled');

Route::middleware(["auth", EnsurePhoneNumberIsVerified::class])->group(function() {
    Route::get('/assets/{image}', function ($image) {
        $width = request('w') ?? 200;
        $height = request('h');

        return Image::make(public_path("/images/$image"))
            ->resize($width, $height, fn($constrains) => $constrains->aspectRatio())
            ->response();
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    Route::get('/announcements/{announcement}/edit', AnnouncementForm::class);
    Route::get('/announcements/create', AnnouncementForm::class);

    Route::get('/conditions/create', ConditionForm::class);
    Route::get('/conditions/{condition}/edit', ConditionForm::class);
    Route::get('/conditions/{type}', TypeConditions::class);
    Route::get('/types/create', TypesForm::class);
    Route::get('/types/{type}/edit', TypesForm::class);
    Route::get('/types', TypesTable::class);

    Route::resource("{eventType}", EventsController::class)
        ->parameters(['{eventType}' => 'event', 'eventType' => 'eventType'])
        ->only(['create', 'edit', 'index', 'store', 'update', 'destroy']);
});
