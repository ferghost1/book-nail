<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AdminRequest;

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

    public function saveEmployee(AdminRequest $req) {
    	$data = $req->only('id', 'location_id', 'user_name', 'password', 'name', 'email', 'phone', 'address', 'is_active');
        if(empty($data['password']))
            unset($data['password']);

        $res = ['success' 	=> false, 'errors'	=> []];
        $validate = $req->validateSaveEmployee($data);
		if ($validate->fails()) {
			$res['errors'] = $validate->errors()->all();
			return $res;
        
        }
        
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
    	$conditions = [
            ['apm.date', '>=', $req->from_date],
            ['apm.location_id', $req->location_id]
        ];

        // can search employee only when have name
    	if (!empty($req->name)) {
    		$keySearch = $req->user_type == 3 ? 'cus.name' : 'emp.name';
    		$conditions[] = [$keySearch, 'like', "%$req->name%"];
    	}

        return [
            'success'   => true,
            'data'      => app('App\Appointment')->getAppointments($conditions, 3)
        ];
    }

    public function saveAppointment(AppointmentRequest $req) {
    	$data = $req->only('id', 'customer_id', 'location_id', 'date', 'service_id', 'employee_id', 'booked_time', 'status');

        $validator = $req->validateSaveAppointment($data);
        if ($validator->fails()) {
            return ['success' => false, 'errors'=> $validator->errors()->all()];
        }

    	return [
    		'success' 	=> true,
    		'data'	  	=> app('App\Appointment')->saveAppointment($data)
    	];
    }

    public function saveServiceEmployee(Request $req) {
        // Validate

        $data = $req->only('service_id', 'employees');
        $serviceEmployees = collect($data['employees'])->map(function($emp) use ($data) {
            return [
                'service_id' => $data['service_id'],
                'employee_id' => $emp['employee_id'],
                'price' => $emp['price'] 
            ];
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
            'data'      => app('App\User')->getUsers($conditions, $page = 1)
        ];
    }

    public function getServiceByLocation(Request $req) {
        $services = app('App\Service')->getServiceByLocation(['location_id' => $req->location_id]);
        $services->load([
            'employees' => function($query) {
                $query->select('users.name', 'users.id', 'service_employees.price');
            }
        ]);

        return [
            'success' => true,
            'data'  => $services
        ];
    }

    public function saveCustomer(Request $req) {
        // Validate
        $data = $req->only('id', 'name', 'phone', 'email', 'address', 'is_trusted', 'is_active');
        if(empty($data['id']))
            return ['success' => false];

        $conditions = [['user_type', 3]];
        
        return [
            'success'   => true,
            'data'      => app('App\User')->saveUser($data, $conditions)
        ];
    }
    
    public function savePageInfo() {

    }
}
