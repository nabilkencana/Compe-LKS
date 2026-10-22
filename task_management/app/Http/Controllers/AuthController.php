<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $req) {
        $val = $req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $usr = User::create($val);

        return response()->json([
            'success' => true,
            'message' => 'Account Registered Successfully.',
            'data' => $usr
        ],201);
    }
    public function login(Request $req) {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $usr = User::firstWhere('email', $req->email);

        if (!$usr || !Hash::check($req->password , $usr->password)) {
            return response()->json([
            'success' => false,
            'message' => 'Incorrect Credentials',
        ],401);
        }

        $tok = $usr->createToken('auth_token')->plainTextToken;
        $usr->token = $tok;
        
        return response()->json([
            'success' => true,
            'message' => 'Login Successfully.',
            'data' => $usr,
        ],200);
    }

    public function logout(Request $req) {
        $req->user()->tokens()->delete();

         return response()->json([
            'success' => true,
            'message' => 'Logout Successfully.',
        ],200);
    }
}
