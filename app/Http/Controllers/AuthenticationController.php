<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password) || $user->status_id == 2) {
            return response()->json([
                'message' => 'E-Mail oder Passwort ungültig'
            ], 403);
        }

        if($user->status_id == 3){
            $user->update([
                'status_id' =>  1
            ]);
        }

        $token = $user->createToken('Login-Token')->plainTextToken;
        $roleId = $user->role_id;
        $roleName = $user->role->name;
        return response()->json([
            'token' => $token,
            'user' => $user,
            'role_id' => $roleId,
            'role_name' => $roleName,
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Erfolgreich abgemeldet'
        ]);
    }
}
