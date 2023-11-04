<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpielGruppenRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $openingHours =  $this->openingHours ? json_decode($this->openingHours, true) : null;
        $formatedOpeningHours = [];
        if (is_array($openingHours)){
            foreach ($openingHours as $openingHour){
                    $formatedOpeningHours[$openingHour['day'].'-'.$openingHour['period']] = $openingHour;
            }
        }
        $this->merge([
            'openingHours'=> $formatedOpeningHours?:$openingHours
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'name' => 'required|string',
            'street_name' => 'required|string',
            'street_number' => 'required|string',
            'postal_code' => 'required',
            'location' => 'required',
            'minimum_occupancy'  => 'required|integer',
            'maximum_occupancy' => 'required|integer',
            'status_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'image' => trans('validation.attributes.image'),
            'name' => trans('validation.attributes.name'),
            'street_name' => trans('validation.attributes.street_name'),
            'street_number' => trans('validation.attributes.street_number'),
            'postal_code' =>trans('validation.attributes.postal_code'),
            'location' => trans('validation.attributes.location'),
            'minimum_occupancy'  => trans('validation.attributes.minimum_occupancy'),
            'maximum_occupancy' => trans('validation.attributes.maximum_occupancy'),
            'status_id' => trans('validation.attributes.status'),
        ];
    }
}
