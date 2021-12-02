<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsNotDisabled
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
        if(auth()->check() && !auth()->user()->isDisabled() && $request->routeIs('disabled')) {
            return redirect('/');
        }

        if($request->routeIs('disabled') || $request->routeIs('logout')) {
            return $next($request);
        }

        if(auth()->check() && auth()->user()->isDisabled()) {
            return redirect()->route('disabled');
        }

        return $next($request);
    }
}
