<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UnauthorizedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            JWTAuth::setToken($token);
            $user = JWTAuth::toUser();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized x2'], 401);
        }

        Auth::login($user);

        return $next($request);
    }
}
