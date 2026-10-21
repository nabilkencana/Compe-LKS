<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $val = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $user = User::create($val);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Account registered successfully.',
            'data' => $user
        ],201);
    }
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::firstWhere('email',$request->email);

        if (!$user || !Hash::check($request->password , $user->password)) {
            return response()->json([
            'success' => false,
            'message' => 'The provided credentials are incorrect.',
        ],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ],
        ],200);
    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ],200);
    }
}
