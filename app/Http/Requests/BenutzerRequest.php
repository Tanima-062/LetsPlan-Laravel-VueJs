<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenutzerRequest extends FormRequest
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
            'image' => 'image|mimes:jpg,jpeg,png|max:5120',
            'role_id' => 'required',
            'street_name' => 'required',
            'house_number' => 'required|string|max:30',
            'postal_code' => 'required|max:10',
            'location' => 'required|string|max:30',
            'email' => 'required|email:filter|unique:users',
            'prefix' => 'required',
            'phone_number' => 'required|max:12|digits_between:1,12',
            'first_name' => ['required', 'regex:/^[\p{L}\p{M}\p{Zs}-]+$/u', 'max:30'],
            'last_name' => ['required', 'regex:/^[\p{L}\p{M}\p{Zs}-]+$/u', 'max:30'],
        ];
    }
    public function attributes()
    {
        return [
            'image' => trans('validation.attributes.image'),
            'role_id' => trans('validation.attributes.role'),
            'status_id' => trans('validation.attributes.status'),
            'street_name' => trans('validation.attributes.street_name'),
            'house_number' => trans('validation.attributes.street_number'),
            'postal_code' =>trans('validation.attributes.postal_code'),
            'location' => trans('validation.attributes.location'),
            'email' => trans('validation.attributes.email'),
            'phone_number' => trans('validation.attributes.phone'),
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
        ];
    }
}
