<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use Cookie;
use DB;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $userType = null, $redirectUrl = null)
    {   
        // 1 computer can be login many type of user
        switch ($userType) {
            case 1:
                $authorize = Cookie::get('Ad-Authorization');
                break;
            case 2:
                $authorize = Cookie::get('Emp-Authorization');
                break;
            case 3:
                $authorize = Cookie::get('Cus-Authorization');
                break;
            default:
                die('cmm he');
        }

        $conditions = [
            ['api_token', $authorize],
            ['user_type', $userType]
        ];
        $user = User::where($conditions)->whereNotNull('api_token')->first();

        if ($user) {
            Auth::login($user);
            return $next($request);
        } else {
            return $redirectUrl ? redirect($redirectUrl) : die('mm');
        }

    }
}
