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

    public function saveService($data) {
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
		    $this->delete();
		}, 2);
    }
}
