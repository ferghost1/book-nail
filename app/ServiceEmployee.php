<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ServiceEmployee extends Model
{
    protected $table = 'service_employees';
    protected $fillable = [
    	'service_id', 'employee_id'    
    ];

    public function saveServiceEmployee($data, $serviceId) {
    	
    	$a = DB::transaction(function () use ($data, $serviceId){
		    $this->where('service_id', $serviceId)->delete(); 
		    return $this->insert($data);
		});

		return $data;
    }
}
