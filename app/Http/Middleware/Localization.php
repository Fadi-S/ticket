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
        $locales = $this->locales();
        $user = $request->user();
        $cookieLocale = isset($_COOKIE['locale']) ? $_COOKIE['locale'] : null;

        if($user && $cookieLocale && $this->isValidLocale($cookieLocale) && $user->locale !== $cookieLocale) {
            $user->locale = $cookieLocale;
            $user->save();
        }

        $locale = $user->locale ?? $cookieLocale ?? 'ar';

        if(! $this->isValidLocale($locale)) {
            $locale = 'ar';
        }

        app()->setLocale($locale);
        View::share('dir', __('ltr'));

        $number = \NumberFormatter::create($locale, \NumberFormatter::DECIMAL);
        App::singleton('num', fn() => $number);
        View::share('num', $number);

        App::singleton('locales', fn() => $locales);
        \View::share('locales', $locales);

        return $next($request);
    }

    public function locales() : array
    {
        return [
            'ar' => [
                'name' => 'اللغة العربية',
                'logo' => asset('/images/flags/egypt.svg'),
            ],
            'en' => [
                'name' => 'English',
                'logo' => asset('/images/flags/usa.svg'),
            ],
        ];
    }

    public function isValidLocale($locale) : bool
    {
        return in_array($locale, array_keys( $this->locales() ));
    }
}
