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

	public function getServiceByLocation(Request $req) {
		$services = app('App\Service')->getServiceByLocation(['location_id' => $req->location_id]);

		return [
			'success' => true,
			'data' => $services
		];
	}

	public function getServiceRelation(Request $req) {
		$conditions = [['services.location_id', $req->location_id]];
		$serviceRelation = app('App\Service')->getServiceRelation($conditions);

		return [
			'success' => true,
			'data' => $serviceRelation
		];
	}

	public function getServiceEmployees() {
		$res['data'] = app('App\Service_employee')->all();
		$res['success'] = true;
		return $res;
	}

	public function getEmployeeByLocation(Request $req) {
		$condition = [
			['location_id', $req->location_id],
			['user_type', 2]
		];

		return [
			'success' => true,
			'data' => app('App\User')->where($condition)->get()
		];
	}
	
	public function getEmployeeBookedTime(Request $req) {
		$bookedTime = app('App\Appointment')->getEmployeeBookedTime($req->employee_id, $req->date, $req->except ?? []);
		return [
			'success' 	=> true,
			'data'		=> $bookedTime
		];
	}

	public function getCustomerAppointments(Request $req) {
		$conditions = [
			['apm.customer_id', Auth::user()->id],
			['apm.date', '>=', $req->from_date ?? Carbon::now()->format('Y-m-d')]
		];

		return [
			'success'	=> true,
			'data'		=> app('App\Appointment')->getAppointments($conditions)
		];
	}


	public function book(Request $req) {
		// check when update whether appoitmennt is belong to customer and is it can be edit
		// can not edit when decline
		// can edit when user was blocked
		// validate here

		$bookData = $req->only('id', 'location_id', 'service_id', 'employee_id', 'date', 'time_space');
		$bookData['customer_id'] = Auth::user()->id;
		$bookData['status'] = Auth::user()->is_trusted ? 2 : 1;
		$bookedTime = app('App\Appointment')->getEmployeeBookedTime($bookData['employee_id'], $bookData['date'], [$bookData['id'] ?? '']);
		if ($bookData['status'] == 2) {
			$bookData['booked_time'] = min(array_diff($bookData['time_space'], $bookedTime));
		} else {
			$bookData['booked_time'] = 0;
		}
		$res['data'] = app('App\Appointment')->saveAppointment($bookData);
		$res['success'] = true;
		return $res;
	}

}