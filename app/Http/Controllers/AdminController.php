<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function saveLocation(Request $req) {
    	// Validate

    	$data = $req->only('id', 'name', 'address', 'phone', 'description');
		$res['data'] = app('App\Location')->saveLocation($data);
		$res['success'] = true;
		return $res;
    }

    public function deleteLocation(Request $req) {
    	$location = app('App\Location')->findOrFail($req->id);
    	// check if there are appointments not solved 
    	$appointments = app('App\Appointment')
    		->where([
    			['location_id', $location->id],
    			['status', 2],
    			['date', '>=', Carbon::now()]
    		])->get();

    	if($appointments->isEmpty()) {
    		$location->deleteWithRelation();
    		return ['success' => true];
    	} else {
    		return ['success' => false, 'messsage' => 'There are appointments not solve in this location'];
    	}
    }

    public function saveService(Request $req) {
    	// Validate

    	$data = $req->only('id', 'name', 'location_id');
		$res['data'] = app('App\Service')->saveService($data);
		$res['success'] = true;
		return $res;
    }

    public function deleteService(Request $req) {
    	$service = app('App\Service')->findOrFail($req->id);
    	// check if there are appointments not solved 
    	$appointments = app('App\Appointment')
    		->where([
    			['service_id', $service->id],
    			['status', 2],
    			['date', '>=', Carbon::now()]
    		])->get();

    	if($appointments->isEmpty()) {
    		$service->deleteWithRelation();
    		return ['success' => true];
    	} else {
    		return ['success' => false, 'messsage' => 'There are appointments picked this service not solved yet'];
    	}
    }

    public function saveEmployee(Request $req) {
    	// Validate

    	$data = $req->only('id', 'user_name', 'password', 'name', 'email', 'phone', 'is_active');
    	$data['user_type'] = 2;
    	$res['data'] = app('App\User')->saveUser($data);
		$res['success'] = true;
		return $res;
    }

    public function deleteEmployee(Request $req) {
    	$employee = app('App\User')->where('user_type', 2)->findOrFail($req->id);
    	// check if there are appointments not solved 
    	$appointments = app('App\Appointment')
    		->where([
    			['employee_id', $employee->id],
    			['status', 2],
    			['date', '>=', Carbon::now()]
    		])->get();

    	if($appointments->isEmpty()) {
    		$employee->deleteWithRelation();
    		return ['success' => true];
    	} else {
    		return ['success' => false, 'messsage' => 'There are appointments picked this employee not solved yet'];
    	}
    }

    public function getAppointments(Request $req) {
    	// validate
        if(session('key')) {
            echo 'co luu' . session('key');
        } else {
            echo 'save moi ' . session('key', 123456);
        }
    	$conditions = [['apm.date', '>=', $req->from_date]];
    	if (!empty($req->name)) {
    		$keySearch = $req->user_type == 3 ? 'cus.name' : 'emp.name';
    		$conditions[] = [$keySearch, 'like', "%$req->name%"];
    	}
    	$res['data'] = $query = app('App\Appointment')->getAppointments($conditions);
        return $res;
    }

    public function saveAppointment(Request $req) {
    	$data = $req->only('id', 'customer_id', 'location_id', 'date', 'service_id', 'employee_id', 'booked_time', 'status');

    	return [
    		'success' 	=> true,
    		'data'	  	=> app('App\Appointment')->saveAppointment($data)
    	];
    }

    public function saveServiceEmployee(Request $req) {
        // Validate

        $data = $req->only('service_id', 'employees');
        $serviceEmployees = collect($data['employees'])->map(function($emp) use ($data) {
            return array_merge($emp, ['service_id' => $data['service_id']]);
        })->toArray();

        app('App\ServiceEmployee')->saveServiceEmployee($serviceEmployees, $data['service_id']);
        return [
            'success'   => true
        ];
    }

    public function getUsers(Request $req) {
        // validate

        $data = $req->only('name', 'user_type');
        $conditions = [['user_type', $data['user_type']]];
        if (!empty($data['name'])) {
            $conditions[] = ['name', 'like', "%{$data['name']}%"];
        }

        return [
            'success'   => true,
            'data'      => app('App\User')->getUsers($conditions)
        ];
    }

    public function savePageInfo() {

    }
}
