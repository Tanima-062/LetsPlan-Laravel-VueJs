<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required|string|max:40',
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'telephone' => 'max:12',
            'email' => 'required|email:filter|unique:companies'

        ];
    }
    public function attributes()
    {
        return [
            'name' => trans('validation.attributes.name'),
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
            'email' => trans('validation.attributes.email'),
            'telephone' => trans('validation.attributes.telephone'),

        ];
    }
}
