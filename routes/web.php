<?php


use App\Http\Controllers\EventsController;
use App\Http\Livewire\DuplicatesTable;
use App\Http\Livewire\Friends;
use App\Http\Livewire\MakeReservation;
use App\Http\Livewire\ResetPasswordByPhone;
use App\Http\Livewire\Tickets;
use App\Http\Livewire\Users\UsersTable;
use App\Http\Livewire\VerifyPhoneNumber;
use App\Http\Middleware\EnsurePhoneNumberIsVerified;
use App\Http\Middleware\UnVerified;
use App\Http\Controllers\Admin\{AuthController, DashboardController, UsersController};
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

Route::get('/verify', VerifyPhoneNumber::class)
    ->middleware(['auth', UnVerified::class]);

Route::middleware(["auth", EnsurePhoneNumberIsVerified::class])->group(function() {
    Route::get('/assets/{image}', function ($image) {
        $width = request('w') ?? 200;
        $height = request('h');

        return Image::make(public_path("/images/$image"))
            ->resize($width, $height, fn($constrains) => $constrains->aspectRatio())
            ->response();
    });

    Route::get('/duplicates', DuplicatesTable::class);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('users/create', UserForm::class);
    Route::get('users/{user}/edit', UserForm::class);

    Route::resource("users", UsersController::class)
        ->except('create', 'edit', 'index');

    Route::get('/users', UsersTable::class);

    $eventResources = ['create', 'edit', 'index', 'store', 'update'];
    Route::resource("masses", EventsController::class)
        ->parameters(['masses' => 'event'])
        ->only($eventResources);
    Route::resource("kiahk", EventsController::class)
        ->parameters(['kiahk' => 'event'])
        ->only($eventResources);
    Route::resource("vespers", EventsController::class)
        ->parameters(['vespers' => 'event'])
        ->only($eventResources);
    Route::resource("baskha", EventsController::class)
        ->parameters(['baskha' => 'event'])
        ->only($eventResources);
    Route::resource("holy", EventsController::class)
        ->parameters(['holy' => 'event'])
        ->only($eventResources);

    Route::get("tickets", Tickets::class);

    Route::get('/reserve', MakeReservation::class);

    Route::get('/logs', [DashboardController::class, 'showLogs']);

    Route::get("ajax/reservation/users", [\App\Http\Controllers\API\ReservationsController::class, 'getUsers']);
    Route::get("ajax/reservation/events", [\App\Http\Controllers\API\ReservationsController::class, 'getEvents']);

    Route::get('/friends', Friends::class);
});

$redirectAdmin = function () {
    $time = Cookie::get('hah') ?? 0;

    $replies = collect([
        "Please don't hack me!",
        "Hi! I'm Mr.Robot",
        "Looking for smth?",
        "Okay, you are really trying!",
        "Can you please stop..",
        "Don't be evil :(",
    ]);

    Cookie::queue('hah', $time+1, 24*365);

    $index = $replies->count() > $time ? $time : $replies->count()-1;
    return $replies->get($index);
};

Route::get('/admin', $redirectAdmin);
Route::get('/wp-admin', $redirectAdmin);

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
