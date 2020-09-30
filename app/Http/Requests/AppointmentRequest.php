<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    const ATTRIBUTES = [
        'service_id'    => 'Service',
        'employee_id'   => 'Employee',
    ];
    const MESSAGES = [
        'required' => 'Please choose :attribute',
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

    public function validateSaveAppointment($data) {
        return Validator::make($data, 
            [
                'customer_id' => 'required',
                'location_id' => 'required',
                'date' => 'required',
                'service_id' => 'required',
                'employee_id' => 'required',
                'status' => 'required',
                'booked_time' => [
                    function($attrs, $value, $messageFail) use ($data) {
                        if($data['status'] == 2 && !$value)
                            $messageFail('You need set time for customer when approve this appointment'); 
                    }, 
                    'required'
                ],
            ],
            self::MESSAGES, self::ATTRIBUTES
        );
    }
}
