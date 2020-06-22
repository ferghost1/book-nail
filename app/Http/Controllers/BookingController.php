<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Appointment;

class BookingController extends Controller
{
	public function getLocations() {
		$res['data'] = app('App\Location')->all();
		$res['success'] = true;
		return $res;
	}

	public function getServices() {
		$res['data'] = app('App\Service')->all();
		$res['success'] = true;
		return $res;
	}

	public function getServiceEmployees() {
		$res['data'] = app('App\Service_employee')->all();
		$res['success'] = true;
		return $res;
	}
	
	public function getEmployeeBooked(Request $req) {
		$employee_id = $req->employee_id;
		$bookedTime = app('App/Appointment')
			->where([['status', 2], ['employee_id', $employee_id]])->get();
		$bitBookedTime = 0;
		foreach ($bookedTime as $time) {
			$bitBookedTime = $bitBookedTime | $time;
		}
		return $bitBookedTime;
	}

	public function getCustomerAppointments() {
		$getBy = ['apm.customer_id' => Auth::user()->id];
		return app('App/Appointment')->getAppointments($getBy);
	}


	public function book(Request $req) {
		// validate here
		$bookData = $req->only('id', 'service_id', 'employee_id', 'date', 'time_space');
		$bookData['customer_id'] = Auth::user()->id;
		$bookData['status'] = Auth::user()->is_trusted ? 2 : 1;
		$bookedTime = app('App\Appointment')->getEmployeeBooked($bookData['employee_id'], $bookData['date']);
		if ($bookData['status'] == 2) {
			$bookedData['booked_time'] = min(array_diff($bookData['time_space'], $bookedTime));
		} else {
			$bookedData['booked_time'] = 0;
		}
		$res['data'] = app('App\Appointment')->saveAppointment($bookData);
		$res['success'] = true;
		return $res;
	}

}
