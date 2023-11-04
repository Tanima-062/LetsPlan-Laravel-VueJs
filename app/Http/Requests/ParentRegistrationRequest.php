<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'child_first_name' => 'required',
            'child_last_name' => 'required',
            'gender_id' => 'required',
            'child_birthday' => 'required|date',
            'nationality_id' => 'required',
            'mother_tongue_id' => 'required_if:other_mother_tongue,null',
            'other_mother_tongue' => 'required_if:mother_tongue_id,null',
            'mother_first_name' => 'required',
            'mother_last_name' => 'required',
            'father_first_name' => 'required',
            'father_last_name' => 'required',
            'street' => 'required',
            'street_number' => 'required|integer',
            'postal_code' => 'required|integer',
            'place' => 'required',
            'email' => 'required|email',
            'phone_number_prefix' => 'required|max:3',
            'phone_number' => 'required|integer|digits:9',
            'mobile_phone_number_prefix' => 'required|max:3',
            'mobile_phone_number' => 'required|digits:9',
            'semester_id' => 'required',
            'membership_id' => 'required',
            'note' => 'max:200',
            'openingHours' => 'required'
        ];
    }
}
