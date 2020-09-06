<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    const ATTRIBUTES = [
        'service_id'    => 'Service',
        'employee_id'   => 'Employee',
        'time_space'    => 'Free time'
    ];
    const MESSAGES = [
        'required' => 'Please choose :attribute',
        'customer_id.required'  => 'Please login first',
        'booked_time.required_if' => 'Please set time if approved this appointment'
    ];

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function validateBook($data) {
        $afterDate = Carbon::now()->format('y-m-d');
        return Validator::make($data, 
            [
                'location_id'   =>  'required',
                'date'          =>  "required|date|after_or_equal:$afterDate",
                'service_id'    =>  'required',
                'employee_id'   =>  'required',
                'time_space'    =>  'required|array',
                'customer_id'   =>  'required'
            ],
            self::MESSAGES, self::ATTRIBUTES
        );
    }
}
