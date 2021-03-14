<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($locale = $request->user('web')->locale ?? \Cookie::get('locale') ?? 'ar');

        $number = \NumberFormatter::create(app()->getLocale(), \NumberFormatter::DECIMAL);
        View::share('num', $number);
        View::share('dir', $locale === 'ar' ? 'rtl' : 'ltr');
        App::singleton('num', fn() => $number);

        return $next($request);
    }
}
