<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{


    public function register(Request $request) {
        $validated = $request->validate([
            'username' => 'required|unique',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Register success'
        ],200);
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|unique',
            'password' => 'required|confirmed'
        ]);

        $user = User::where('email' , $request->email)->first();

        if (!$user || !Hash::check($request->password , $user->password)) {
            return response()->json([
            'success' => false,
            'message' => 'Username or password incorrect'
        ],401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Register success'
        ],200);
    }

    public function logout(Request $request) {
        $request->user()->curentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout success'
        ],200);
    }
}
