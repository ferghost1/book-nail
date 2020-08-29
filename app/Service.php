<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'name', 'location_id'
    ];

    public function service_employees() {
    	return $this->hasMany('App\ServiceEmployee');
    }

    public function employees() {
        return $this->hasManyThrough('App\User', 'App\ServiceEmployee', 'service_id', 'id', 'id', 'employee_id');
    }

    public function saveService($data) {
    	if (!empty($data['id'])) {
			$obj = $this->find($data['id']);
			$obj->fill($data);
		} else {
			$obj = new Service($data);
		}
		
		$obj->save();
		return $obj;
    }

    public function getServiceByLocation($data) {
        $condition = [
            ['location_id', $data['location_id']]
        ];

        return app('App\Service')->where($condition)->get();
    }

    public function getServiceRelation($conditions = []) {
        $conditions[] = ['user_type', 2];
        return app('App\Service')
        ->join('service_employees as se', 'se.service_id', '=', 'services.id')
        ->join('users as u', 'u.id', '=', 'se.employee_id')
        ->select('se.*', 'u.name as emp_name')
        ->where($conditions)
        ->get();
    }

    public function deleteWithRelation() {
    	DB::transaction(function () {
		    $this->service_employees()->delete();
		    $this->delete();
		}, 2);
    }
}
