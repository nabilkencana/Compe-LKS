<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $r) {
        $val = $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $usr = User::create($val);
        $usr->refresh();

        return response()->json([
            'message' => 'Register successful.',
            'data' => $usr
        ],201);
    }

    public function login(Request $r) {
        $r->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $usr = User::firstWhere('email' , $r->email);
        if (!$usr || !Hash::check($r->password , $usr->password)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ],401);
        }

        $tok = $usr->createToken('auth_token')->plainTextToken;
        $usr->token = $tok;

        return response()->json([
            'message' => 'Login successful.',
            'data' => $usr
        ]);
    }

    public function logout(Request $r) {
        $r->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful.',   
        ]);
    }
}
