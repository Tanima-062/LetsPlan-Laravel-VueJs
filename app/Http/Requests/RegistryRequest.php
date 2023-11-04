<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistryRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'registration_phase_start_date' => 'required|date',
            'registration_phase_end_date' => 'required|date|after:registration_phase_start_date',
            'playground_period_start_date' => 'required|date|after:registration_phase_start_date',
            'playground_period_end_date' => 'required|date|after:playground_period_start_date',
            'parents_inform_date' => 'required|date',
            'open_day_date' => 'required|date',
            'open_day_time_from' => 'required',
            'open_day_time_to' => 'required|after:open_day_time_from',
        ];
    }
    public function attributes()
    {
        return [
            'registration_phase_start_date' => trans('validation.attributes.registration_phase_start_date'),
            'registration_phase_end_date' =>trans('validation.attributes.registration_phase_end_date'),
            'playground_period_start_date' => trans('validation.attributes.playground_period_start_date'),
            'playground_period_end_date' => trans('validation.attributes.playground_period_end_date'),
            'parents_inform_date' => trans('validation.attributes.parents_inform_date'),
            'open_day_date' => trans('validation.attributes.open_day_date'),
            'open_day_time_from' => trans('validation.attributes.open_day_time_from'),
            'open_day_time_to' => trans('validation.attributes.open_day_time_to'),
        ];
    }
}
