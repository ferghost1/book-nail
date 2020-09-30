<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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

    public function validateSaveEmployee($data) {
        return Validator::make($data, 
            [
                'user_name' => ['required', Rule::unique('users')->ignore($data['id'] ?? 0)],
                'phone'     => 'digits_between:9,15',
                'is_active' => 'required'
            ],
            self::MESSAGES, self::ATTRIBUTES
        );
    }
}
