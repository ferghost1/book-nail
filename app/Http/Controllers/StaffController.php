<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class StaffController extends Controller
{
    public function getOwnAppointments(Request $req) {
    	// Validate
    	$conditions = [
    		['apm.date', '>=', $req->from_date],
    		['emp.id', Auth::user()->id]
    	];
    	// dd($conditions);
    	return [
    		'success' 	=> true,
    		'data'		=> app('App\Appointment')->getAppointments($conditions)
    	];
    }
}
