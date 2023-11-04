<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function show()
    {
        return new UserResource(\auth()->user());
    }

    public function update(UserProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        if (\Illuminate\Support\Facades\Request::hasFile('image')) {
            $user->update([
                'image' => \Illuminate\Support\Facades\Request::file('image')->store('users')
            ]);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_notifications' => $request->email_notifications
        ]);

        return response()->json('Benutzer erfolgreich aktualisiert');
    }

     public function getmax()
    {

        $last_id =User::max('id');
        $last_id = $last_id+'1';
        $p_uniq_id = 'BN'.strval(str_pad($last_id,5,0,STR_PAD_LEFT));

        return response()->json($p_uniq_id);
    }

}
