<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    const ATTRIBUTES = [
    ];
    const MESSAGES = [
        'required' => 'Please fill your :attribute'
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

    public function validateUpdateCustomerProfile($data) {
        return Validator::make($data, 
            [
                'name'      => 'required',
                'address'   => 'required',
                'phone'     => 'digits_between:9,15'
            ],
            self::MESSAGES, self::ATTRIBUTES
        );
    }
}
