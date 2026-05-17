<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Check password and account status
        if (!$user || !Hash::check($request->password, $user->password) || !$user->status) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email/password or account is deactivated.'
            ], 401);
        }

        // Generate Sanctum Token for Flutter session management
        $token = $user->createToken('flutter_auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ], 200);
    }

    public function logout(Request $request) {
        // Flutter user exits -> destroy current token instance
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Token revoked. Logged out successfully.'
        ]);
    }
}