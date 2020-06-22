<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Hash;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'social_id', 'social_type', 'is_trusted', 'is_active',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function service_employees() {
        return $this->hasMany('App\ServiceEmployee', 'employee_id', 'id');
    }

    public function getUsers($conditions) {
        return $this->where($conditions)->get();
    }

    public function saveUser($data, $where = [], $fields = []) {
        if (!empty($data['id'])) {
            $obj = $this->where($where)
                ->find($data['id']);
            $obj->fill($data);
        } else {
            $obj = new User($data);
        }
        if (!empty($data['password']))
            $obj->password = Hash::make($data['password']);
        
        $obj->save();
        return $fields ? $obj->only($fields) : $obj;
    }

    public function deleteWithRelation() {
        if ($this->user_type == 2) {
            DB::transaction(function () {
                $this->service_employees()->delete();
                $this->delete();
            }, 2);
        }
    }
}
