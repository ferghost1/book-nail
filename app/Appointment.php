<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = [
    	'id', "customer_id", "date", "service_id", "employee_id", "time_space", "booked_time", "status", "location_id"
    ];
    protected $casts = [
        'time_space' => 'object',
        'booked_detail' => 'object'
    ];

    public function getAppointments($conditions = [], $paginate = 0) {
    	$res = $this->from('appointments as apm')
    		->leftJoin('users as emp', 'apm.employee_id', 'emp.id')
    		->leftJoin('users as cus', 'apm.customer_id', 'cus.id')
    		->where($conditions)
    		->select('apm.*', 'cus.name as cus_name', 'emp.name as emp_name');
    	return $paginate ? $res->paginate($paginate) : $res->get();
    }

    public function getEmployeeBookedTime($employee_id, $date, $except = []) {
    	return $this->where([['status', 2], ['employee_id', $employee_id], ['date', $date]])
    		->whereNotIn('id', $except)
    		->pluck('booked_time')->toArray();
	}

	public function saveAppointment($data, $conditions = []) {
		if (!empty($data['id'])) {
			// check whether appointment is owned by customer
			$obj = $this->where($conditions)
				->find($data['id']);
			$obj->fill($data);

			//Make detail if something change
			if ($obj->isDirty('location_id') || $obj->isDirty('service_id') || $obj->isDirty('employee_id')) {
				$obj->booked_detail = $this->makeAppointmentDetail($obj);
			}
		} else {
			$obj = new Appointment($data);
			$obj->booked_detail = $this->makeAppointmentDetail($obj);
		}
		$obj->save();
		return $obj;
	}

	public function makeAppointmentDetail($obj) {
		$location = app('App\Location')->findOrFail($obj->location_id);
		return [
			'location_name' => $location->name,
			'service_name'	=> $location->services()->find($obj->service_id)->name ?? 'Unknown service',
			'employee_name'	=> $location->employees()->find($obj->employee_id)->name  ?? 'Unknown employee',
			'price'			=> $location->service_employees()->where([
									['service_id', $obj->service_id],
									['employee_id', $obj->employee_id]
								])->first()->price ?? 'Unknown price'
		];
	}
}
