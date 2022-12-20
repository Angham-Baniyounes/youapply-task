<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->json(
            [
                'status' => 'success', 
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ],
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $fieldType = NULL;

        if(is_numeric($request->username)) {
            $fieldType = 'phone_number';
        } elseif(filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } else {
            $fieldType = 'username';
        }

        $user = User::where($fieldType, $request->username)->first();

        if ($user && auth()->attempt([$fieldType => $request->username, 'password' => $request->password])) {
            $token = $user->createToken('apiToken')->plainTextToken;
            return response()->json(
                [
                    'status' => 'success',
                    'data' => [
                        'user' => $user,
                        'token' => $token
                    ]
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'incorrect input fields'
                ],
                401
            );
        }
    }

    public function getUserInfo()
    {
        $user = auth()->user();
        return [
            'data' =>  $user 
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }
}
