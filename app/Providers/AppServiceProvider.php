<?php

namespace App\Providers;

use App\Models\Login;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if(!isset($_COOKIE['dark'])) {
            $isDark = now()->lte(Carbon::parse('30th April 2021 06:00pm'));
        }else {
            $isDark = $_COOKIE['dark'] === 'true';
        }

        \View::share('isDark', $isDark);
    }
}
