<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlannungRequest extends FormRequest
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
            'spielgruppen_id_to' => 'required',
            'day_id_from' => 'required',
            'day_id_to' => 'required',
            'hour_id_from' => 'required',
            'hour_id_to' => 'required',
            'children_registration_id' => 'required'
        ];
    }
}
