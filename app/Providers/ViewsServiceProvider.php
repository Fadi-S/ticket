<?php

namespace App\Providers;

use App\Models\EventType;
use Illuminate\Support\ServiceProvider;

class ViewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->shareTypesWithViews();
    }

    private function shareTypesWithViews()
    {
        \View::composer('components.master', function ($view) {
            $view->with([
                'shownTypes' => EventType::getShown(),
            ]);
        });
    }
}
