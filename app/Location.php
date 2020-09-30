<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = [
        'name', 'address', 'phone', 'description'
    ];

    public function services() {
    	return $this->hasMany('App\Service');
    }

    public function service_employees() {
    	return $this->hasManyThrough('App\ServiceEmployee', 'App\Service');
    }

    public function employees() {
    	return $this->hasMany('App\User')->where('user_type', 2);
    }

    public function saveLocation($data) {
    	if (!empty($data['id'])) {
			$obj = $this->find($data['id']);
			$obj->fill($data);
		} else {
			$obj = new Location($data);
		}
		
		$obj->save();
		return $obj;
    }

    public function deleteWithRelation() {
    	DB::transaction(function () {
		    $this->service_employees()->delete();
		    $this->services()->delete();
		    $this->employees()->delete();
		    $this->delete();
		}, 2);
    }
}
