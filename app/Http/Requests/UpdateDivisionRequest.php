<?php

namespace App\Http\Requests;

use App\Models\Division;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDivisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
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
            'registration_start_date' => 'required|date',
            'registration_end_date' => 'required|date',
            'playgroup_start_date' => 'required|date|after:registration_start_date',
            'playgroup_end_date' => 'required|date',
            'date_parent' => 'required|date',
            'open_door_date' => 'required|date',
            'open_door_start_time' => 'required|string|max:12',
            'open_door_end_time' => 'required|max:12',
            'place' => 'required'

        ];
    }
    public function attributes()
    {
        return [
            'name' => trans('validation.attributes.name'),
            'registration_start_date' => trans('validation.attributes.registration_start_date'),
            'registration_end_date' => trans('validation.attributes.registration_end_date'),
            'playgroup_start_date' => trans('validation.attributes.playgroup_start_date'),
            'playgroup_end_date' => trans('validation.attributes.playgroup_end_date'),
            'date_parent' => trans('validation.attributes.date_parent'),
            'open_door_date' => trans('validation.attributes.open_door_date'),
            'open_door_start_time' => trans('validation.attributes.open_door_start_time'),
            'open_door_start_time' => trans('validation.attributes.open_door_start_time'),
            'place' => trans('validation.attributes.place')
        ];
    }


    /**
     * Validate registration start & end period
     *
     * @param [type] $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $divisions = Division::query()
                ->where('company_id', Auth::user()->company_id)
                ->where('registration_start_date', '<=', $this->registration_end_date)
                ->where('registration_end_date', '>=', $this->registration_start_date)
                ->where('id', "<>", $this->division->id)
                ->exists();
            if ($divisions) {
                $validator->errors()->add('registration_period_duplicate', 'Registration period duplicate');
            }
        });
    }
}
