<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Cookie;
use App\User;
use App\Http\Requests\UserRequest; 

class UserController extends Controller
{
	private $model;

	public function __construct() {
		$this->model = app('App\User');
	}

    public function checkCusLogin(Request $req) {
        $authorize = Cookie::get('Cus-Authorization');
        $conditions = [
            ['api_token', $authorize],
            ['user_type', 3],
            ['api_token', '!=', ''],
            ['is_active', 1]
        ];
        $user = User::where($conditions)->whereNotNull('api_token')->first();

        if ($user) {
            $res['success'] = true;
            $res['data'] = Auth::user()->only('id', 'name', 'is_trusted', 'phone', 'address');
            return response($res)->cookie('Cus-Authorization', $user->api_token, 720);
        } else {
            return ['success' => false];
        }

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
        
        // Check be blocked
        if($user->is_active == 0) {
            return ['success' => false, 'error' => 'You was blocked, please contact admin for more detail'];
        }

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
        return response(['success' => true])->cookie('Cus-Authorization', '', 720);
    }

    public function updateCustomerProfile(UserRequest $req) {
        $res = ['success' => false, 'errors' => []];
        $data = $req->only('name', 'phone', 'address');
        $data['id'] = Auth::user()->id;

        $validate = $req->validateUpdateCustomerProfile($data);
		if ($validate->fails()) {
			$res['errors'] = $validate->errors()->all();
			return $res;
		}
        
        $res['data'] = app('App\User')->saveUser($data)->only(array_keys($data));
        $res['success'] = true;
        return $res;
    }

}
