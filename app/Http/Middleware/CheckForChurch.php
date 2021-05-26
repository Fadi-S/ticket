<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForChurch
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
        if($request->user() && !$request->user()->church_id) {
            return redirect()->to('/choose-church');
        }

        return $next($request);
    }
}
