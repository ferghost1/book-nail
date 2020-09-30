<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedirectAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $userType = null, $url)
    {   
        dd(Auth::user());
        if (Auth::check() &&  Auth::user()->user_type == $userType) {
            return $next($request);
        }

        return redirect($url);
    }
}
