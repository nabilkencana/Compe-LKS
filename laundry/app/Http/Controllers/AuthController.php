<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req) {
        $val = $req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role' => 'nullable|in:customer,staff,admins'
        ]);

        $user = User::create($val);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Account registered successfully.',
            'data' => $user
        ],201);
    }
    public function login(Request $req) {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::firstWhere('email' , $req->email);

         if (!$user || ! Hash::check($req->password , $user->password)) {
            return response()->json([
                'success' => false,
            'message' => 'The provided credentials are incorrect.'
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
            ]
        ]);
    }
    public function logout(Request $req) {
        $req->user()->tokens()->delete();
        return response()->noContent();
    }

}
