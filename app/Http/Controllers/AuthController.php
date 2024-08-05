<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Define validation rules
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create a new user record
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401); // Return 401 Unauthorized status
        }

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and token type
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Logout the user
    public function logout(Request $request)
    {
        // Retrieve the token from the Authorization header
        $request->bearerToken();

        // Check if the token is valid
        $userToken = Auth::user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->first();

        if (!$userToken) {
            // Return a response indicating the token is invalid
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Revoke the user's current token
        Auth::user()->tokens()->where('id', $userToken->id)->delete();

        // Return a response indicating successful logout
        return response()->json(['message' => 'Logged out successfully']);
    }
}
