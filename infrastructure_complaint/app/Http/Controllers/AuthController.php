<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function login(Request $req) {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::firstWhere('email', $req->email);

        if (!$user || !Hash::check($req->password , $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
            ],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token =$token;

        return response()->json([
                'success' => true,
                'message' => 'Login Successfully',
                'data' => $user
            ],200);
    }

    public function register(Request $req) {
        $val=$req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $usr = User::create($val);

        $usr->refresh();

        return response()->json([
                'success' => true,
                'message' => 'Registered Successfully',
                'data' => $usr
            ],200);
    }
    public function logout(Request $req) {
        $req->user()->tokens()->delete();
        return response()->json([
                'success' => true,
                'message' => 'Logout Successfully',
            ],200);
    }
}
