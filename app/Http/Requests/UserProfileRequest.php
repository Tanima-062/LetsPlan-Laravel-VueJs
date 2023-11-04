<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
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
        // dd($this->all());
        $rules = [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email_notifications' => 'required',
            'email' => ['required', 'max:255', 'email', Rule::unique('users')->ignore($this->user()->id)],
            'old_password'  =>  ['required_with:password', function($attribute, $value, $fail){
                if ($value && !Hash::check($value, $this->user()->password)) {
                    return $fail('Ungültiges Passwort.');
                }
            }],
            'password'      =>  ['required_with:old_password', 'min:6', 'nullable'],
            'password_confirmation'  =>  ['required_with:password', 'same:password', 'nullable']
        ];
         if ($this->hasFile('image')){
             $rules['image'] = 'image|mimes:jpg,jpeg,png|max:5120';
         }
        // if (!empty($this->password)){
        //     $rules['old_password'] = [
        //         'required',
        //         function ($attribute, $value, $fail) {
        //             if (!Hash::check($value, $this->user()->password)) {
        //                 return $fail('Ungültiges Passwort.');
        //             }
        //         }
        //     ];
        //     $rules['password'] = 'required|min:6|confirmed';
        // }
        return $rules;
    }

    public function messages()
    {
        return [
            'old_password.required_with' => 'Bestehendes Kennwort muss ausgefüllt werden',
            'password_confirmation.required_with' => 'Das neue Kennwort muss bestätigt werden',
            'password_confirmation.same'    =>  'Kennwortbestätigung stimmt nicht mit dem neuen Kennwort überein'
        ];
    }
}
