<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $register = User::create([
            'username'      => $request->username,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        if ($register->save()) {
            return response()->json([
                'success'   => true,
                'message'   => "Register Success!",
                'data'      => $register
            ], 201);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Register Fail!!",
                'data'      => ''
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if (Hash::check($password, $user->password)) {
            $apiToken = base64_encode(Str::random(40));

            $user->update([
                'api_token' => $apiToken
            ]);

            return response()->json([
                'success'   => true,
                'message'   => "Login Success!",
                'data'      => [
                                'user'      => $user,
                                'api_token' => $apiToken
                            ]
            ], 201);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Login Fail!",
                'data'      => ''
            ], 400);
        }
    }
}
