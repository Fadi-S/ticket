<?php

namespace App\Http\Middleware;

use App\Models\Login;
use Closure;
use Illuminate\Http\Request;

class SaveSession
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
        Login::saveCurrentSession();

        return $next($request);
    }
}
