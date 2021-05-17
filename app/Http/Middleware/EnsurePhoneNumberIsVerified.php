<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;

class EnsurePhoneNumberIsVerified
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
        if($request->path() === 'logout') {
            return $next($request);
        }

        if(User::whereBetween('verified_at', [now()->startOfDay(), now()->endOfDay()])->count() >= 340) {
            return $next($request);
        }


        if($request->user() && !$request->user()->isVerified()) {
            return redirect('/verify');
        }

        return $next($request);
    }
}
