<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cache;

class LocaleController extends Controller
{

    public function index($locale)
    {
        if(!in_array($locale, array_keys(app()->make('locales'))))
            abort(404);

        if(auth()->check()) {
            $user = auth()->user();
            $user->locale = $locale;
            $user->save();
        }

        Cache::tags('ticket.users')->flush();

        setcookie('locale', $locale, time()+60*60*24*365*10, '/'); // For 10 years

        return back();
    }

}
