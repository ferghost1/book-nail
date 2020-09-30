<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use Cookie;
use DB;

class CheckBlockedUser
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
        if (!Auth::user()->is_active)
            die(json_encode(['success'=> false, 'error'=> 'You was blocked please contact admin']));
        
        return $next($request);
    }
}
