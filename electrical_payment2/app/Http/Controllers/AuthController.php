<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $req)  {
        $validated = $req->validate([
            'email' => 'required|email',
            'password' => 'required' 
        ]);

        $user = User::where('email' , $req->email)->first();

        if (!$user || !Hash::check($req->password , $user->password)) {
            return response()->json([
                'message' => 'invalid credentials'
            ],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return response()->json([
                'message' => 'Login successful.',
                'data' => $user
            ],200);

    }
    public function register(Request $req)  {
        $val = $req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role' => 'sometimes|in:customer,admin'
        ]);

        $user = User::create($val);
        $user->refresh();

        return response()->json([
                'message' => 'Register successful.',
                'data' => $user
            ],201);

    }
    public function logout(Request $req)  {
        $req->user()->tokens()->delete();
        return response()->json([
                'message' => 'Logout successful.',
            ],200);

    }
    public function me(Request $req)  {
        $user = $req->user();
        return response()->json([
                'message' => 'Logout successful.',
                'data' => $user,
            ],200);

    }

   
}
