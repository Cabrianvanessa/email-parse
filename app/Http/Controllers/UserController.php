<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Get all users
    public function index()
    {
        // Fetch all users
        $users = User::all();
        return response()->json($users);
    }

    // Get a single user by ID
    public function show($id)
    {
        try {
            // Find user by ID
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            // Return 404 response if user not found
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }
}
