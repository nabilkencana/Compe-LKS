<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'username' =>'required|string|min:3',
            'password' =>'required|min:6'
        ]);

        $usr = User::firstWhere('username' , $request->username);

        if (!$usr || !Hash::check($request->password , $usr->password)) {
            return response()->json([
                'status' => 'auth_fail',
                'message' => 'Username or password you entered is incorrect.',
            ],401);
        }

        $token = $usr->createToken('auth_token')->plainTextToken;
        $usr->token = $token;

          return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => $usr
            ],200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

          return response()->json([
                'status' => 'success',
                'message' => 'Logout successful.',
            ],200);
    }
}
