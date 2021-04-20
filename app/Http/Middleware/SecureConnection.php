<?php

namespace App\Http\Middleware;

use Closure;

class SecureConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if(app()->environment() == "production") {
            if (!$request->secure() || !str_starts_with($request->header('host'), 'ticket.')) {
                if (!str_starts_with($request->header('host'), 'ticket.'))
                    $request->headers->set('host', 'ticket.' . $request->header('host'));

                return \Redirect::to(str_replace("http://", 'https://', $request->fullUrl()), 302);
            }
        }*/

        /*if ($request->secure() || !str_starts_with($request->header('host'), 'kat.')) {
            if(!str_starts_with($request->header('host'), 'service.'))
                $request->headers->set('host', 'service.' . $request->header('host'));

            return \Redirect::to(str_replace("https://", 'http://', $request->fullUrl()), 302);
        }*/

        return $next($request);
    }
}
