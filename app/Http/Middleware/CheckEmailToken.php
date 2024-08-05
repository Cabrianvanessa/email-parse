<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckEmailToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            // If no token is provided, return unauthorized response
            return response()->json(['message' => 'No token provided'], 401);
        }

        // Find the user by token
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            // If user not found or token is invalid, return unauthorized response
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Proceed with the request
        return $next($request);
    }
}
