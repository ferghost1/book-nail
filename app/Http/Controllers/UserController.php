<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;

class UserController extends Controller
{
	private $model;

	public function __construct() {
		$this->model = app('App\User');
	}

    public function login(Request $req) {
    	$res = [
    		'success' => false,
    	];
    	// check accesstoken and fb user id
    	$url = "https://graph.facebook.com/$req->userID?fields=name&access_token=$req->accessToken";
    	$fbInfo = json_decode(@file_get_contents($url));
    	// dd($fbInfo);
    	if (!$fbInfo) return $res;
    	
    	// get user info or create if not exist
    	$user = $this->model->firstOrCreate(
    		['social_id' 	=> $fbInfo->id],
    	 	[
    	 		'social_id'	=> $fbInfo->id,
    	 		'name' 		=> $fbInfo->name,
    	 		'social_type' => 'facebook',
    	 		'password'  => Hash::make(123456)
    	 	]
    	 );

    	// Login by auth
    	Auth::attempt(['social_id' => $fbInfo->id, 'password' => 123456]);
    
    	$res['success'] = true;
    	$res['data'] = Auth::user()->only('id', 'name', 'is_trusted', 'phone', 'address');
    	return $res;
    }

    public function logout() {
    	Auth::logout();
    }
}
