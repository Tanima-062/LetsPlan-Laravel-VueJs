<?php

namespace App\Http\Requests;

use App\Models\Division;
use App\Models\Registry;
use App\Models\Spielgruppen;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AnmeldungenUpdateRequest extends FormRequest
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
        $this->merge([
            'openingHours' => $this->openingHours ? json_decode($this->openingHours, true) : []
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'child_first_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'child_last_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'gender_id' => 'required',
            'nationality_id' => 'required',
            'mother_tongue_id' => 'required',
            // 'other_mother_tongue' => 'required_if:mother_tongue_id,null',
            'mother_first_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'mother_last_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'father_first_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'father_last_name' => 'required|regex:/^([^0-9]*)$/|max:30',
            'street' => 'required',
            'street_number' => 'required|string|max:30',
            'postal_code' => 'required|max:10',
            'place' => 'required|max:30|string',
            'place_id' => 'required',
            'division_id' => 'required_without:company_id|exists:divisions,id',
            'email' => 'required|email',
            'phone_number_prefix' => 'required|max:3',
            'phone_number' => 'required|digits_between:1,12',
            'mobile_phone_number_prefix' => 'nullable|max:3',
            'mobile_phone_number' => 'nullable|max:12|digits_between:1,12',
            'semester_id' => 'required',
            'membership_id' => 'nullable',
            'note' => 'nullable|string|max:2000',
            'internal_comments' => 'nullable|string|max:250',
            'spielgruppen_id' => ['required'],
            'child_birthday' => ['required', 'date', function ($attribute, $value, $fail) {
                $company_id =  $this->company_id ?? auth()->user()->company_id;
                $division = Division::where('company_id', $company_id)
                    ->when($this->division_id, fn ($q) => $q->where('id', $this->division_id))
                    ->when($this->company_id, fn ($q) => $q->where('status_id', 2))
                    ->latest()->first();

                if (empty($division)) {
                    return $fail('Please fill the registration star period on homepage');
                }
                if (Carbon::parse($value)->toDateString() > $division->playgroup_start_date->subMonths(30)->toDateString()) {
                    return $fail('Die Spielgruppen Anmeldung ist leider nicht möglich, da Ihr Kind das Mindestalter von 2 1/2 Jahren noch nicht erreicht hat (Geburtsdatum vor oder am ' . $division->playgroup_start_date->subMonths(30)->format('d.m.Y') . '). ');
                }
            }],
            'openingHours' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value)) < (int) request()->input('semester_id')) {
                        return $fail('Bitte geben Sie mindestens so viele mögliche Halbtage an, die der vorhin gewählten gewünschten Male pro Woche entsprechen.');
                    }
                }
            ],
        ];

        if ($this->exists('company_id')) {
            $rules['division_class'] = 'required|accepted';
            $rules['registration_conditions'] = 'required|accepted';
            $rules['confirmation_age'] = 'required|accepted';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'openingHours.required' => 'Bitte geben Sie mindestens so viele mögliche Halbtage an, die der vorhin gewählten gewünschten Male pro Woche entsprechen.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->spielgruppen_id && $this->spielgruppen_id !== 'keine') {
                $play_group = Spielgruppen::where('id', $this->spielgruppen_id)->with('childrenRegistrations')->first();
                $division = $this->division_id ? Division::where('id', $this->division_id)->first() : Division::where('company_id', $play_group->company_id)->where('status_id', 2)->first();

                $selectedSlots = collect($this->openingHours)->map(fn ($item) => ['day_id' => key($item), 'hour_id' => $item[key($item)]]);
                foreach ($selectedSlots as $key => $slot) {
                    //Check if playgroup has the available slot
                    $openingHour = $play_group->openingHours->where('day_id', $slot['day_id'])->where('hour_id', $slot['hour_id'])->first();
                    if (!$openingHour) {
                        $validator->errors()->add('invalid_playgroup', 'Maximum Occupancy Reached');
                        break;
                    }


                    //Check if playgroup reached it's max cap on a particular slot
                    $registered = $play_group->childrenRegistrations->where('division_id', $division->id)->filter(function ($registration) use ($slot) {
                        if($registration->pivot->day_id == $slot['day_id'] && $registration->pivot->hour_id == $slot['hour_id']) {
                            return 1;
                        }
                        return 0;
                    })->values();

                    // info(count($play_group->maximum_occupancy));
                    if (count($registered) >= $play_group->maximum_occupancy) {
                        $validator->errors()->add('invalid_playgroup', 'Maximum Occupancy Reached');
                        break;
                    }
                }
            }
        });
    }


    public function attributes()
    {
        return [
            'child_first_name' => trans('validation.attributes.child_first_name'),
            'child_last_name' => trans('validation.attributes.child_last_name'),
            'gender_id' => trans('validation.attributes.gender'),
            'nationality_id' => trans('validation.attributes.nationality'),
            'mother_tongue_id' => trans('validation.attributes.mother_tongue'),
            'other_mother_tongue' => trans('validation.attributes.image'),
            'mother_first_name' => trans('validation.attributes.mother_first_name'),
            'mother_last_name' => trans('validation.attributes.mother_last_name'),
            'father_first_name' => trans('validation.attributes.father_first_name'),
            'father_last_name' => trans('validation.attributes.father_last_name'),
            'street' => trans('validation.attributes.street_name'),
            'street_number' => trans('validation.attributes.street_number'),
            'postal_code' => trans('validation.attributes.postal_code'),
            'place' => trans('validation.attributes.location'),
            'email' => trans('validation.attributes.email'),
            'phone_number' => trans('validation.attributes.phone'),
            'mobile_phone_number' => trans('validation.attributes.mobile'),
            'semester_id' => trans('validation.attributes.semester'),
            'membership_id' => trans('validation.attributes.membership'),
            'note' => trans('validation.attributes.description'),
            'division_class' => trans('validation.attributes.division_class'),
            'registration_conditions' => trans('validation.attributes.registration_conditions'),
            'confirmation_age' => trans('validation.attributes.confirmation_age'),
        ];
    }
}
