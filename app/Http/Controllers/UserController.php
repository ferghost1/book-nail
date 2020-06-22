<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Cookie;

class UserController extends Controller
{
	private $model;

	public function __construct() {
		$this->model = app('App\User');
	}

    public function cusLogin(Request $req) {
        $res = [
    		'success' => false,
    	];
    	// check accesstoken and fb user id
    	$url = "https://graph.facebook.com/$req->userID?fields=name&access_token=$req->accessToken";
    	$fbInfo = json_decode(@file_get_contents($url));

    	if (!$fbInfo) return $res;

    	// get user info or create if not exist
        $loginToken = Str::random(80);
    	$user = $this->model->firstOrCreate(
    		['social_id' 	=> $fbInfo->id],
    	 	[
    	 		'social_id'	=> $fbInfo->id,
    	 		'name' 		=> $fbInfo->name,
    	 		'social_type' => 'facebook',
    	 		'password'  => Hash::make(123456)
    	 	]
    	 );
        
        // change login token every login
        $user->api_token = $loginToken;
        $user->save();
        Auth::login($user);

    	$res['success'] = true;
    	$res['data'] = Auth::user()->only('id', 'name', 'is_trusted', 'phone', 'address');
    	return response($res)->cookie('Cus-Authorization', $loginToken, 720);
    }

    public function backendLogin(Request $req) {
        if (Auth::attempt($req->only('user_name', 'password'))) {
            $user = Auth::user();
            if (!in_array($user->user_type, [1, 2])) {
                Auth::logout();
                return redirect('login')->withErrors(['error' => 'this account can not login here']);
            }

            $user->api_token = Str::random(80);
            $authType = $user->user_type == 1 ? 'Ad-Authorization' : 'Emp-Authorization';
            if ($user->save()) {
                $cookie = Cookie::make($authType, $user->api_token, 720);
                return redirect($user->user_type == 1 ? 'admin' : 'staff')->withCookie($cookie);
            };
        }

        return redirect('login')->withErrors(['error' => 'wrong acc or password']);
    }

    public function logout() {
    	Auth::logout();
    }

    public function updateCustomerProfile(Request $req) {
        $data = $req->only('name', 'phone', 'email', 'address');
        $data['id'] = Auth::user()->id;
        $res['data'] = app('App\User')->saveUser($data)->only(array_keys($data));
        $res['success'] = true;
        return $res;
    }

}
