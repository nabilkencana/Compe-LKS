<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email' , $request->email)->first();

        if (!$user || !Hash::check($request->password , $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid credentials'
            ],401);
        }

        $user['token'] = $user->createToken('auth_token')->plainTextToken;
         return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => $user,

                ]
            ],200);
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($validated);
        $user->refresh();

         return response()->json([
                'success' => true,
                'message' => 'Registration successful.',
                'data' => $user
            ],201);
    }
    
}
