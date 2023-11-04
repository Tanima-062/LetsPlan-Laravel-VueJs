<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
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
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'company_id' => 'required',
            'email' => 'required|email:filter|unique:users,email,'.$this->user->id

        ];
    }

    public function attributes()
    {
        return [
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
            'company_id' => trans('validation.attributes.company_id'),
            'email' => trans('validation.attributes.email'),


        ];
    }
}
