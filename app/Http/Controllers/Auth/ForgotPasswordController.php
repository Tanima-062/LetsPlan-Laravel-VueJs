<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{
    /**
     * @return array
     */
    public function sendResetLinkEmail()
    {

        $validated =  \Illuminate\Support\Facades\Request::validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')]
        ],
         [
            'email.required' => 'Die eingegebene E-Mail-Adresse ist leider nicht registriert.',
            'email' => 'Die eingegebene E-Mail-Adresse ist leider nicht registriert.',
         ]);

        Password::sendResetLink($validated);

        return [
            'success' => true
        ];
    }
}
