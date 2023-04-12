<?php

namespace App\Http\Middleware;

use App\Models\UserRoles;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
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

        $userRoles = UserRoles::where('user_id', $user->id)->first();

        foreach ($roles as $role) {
            if ($userRoles->hasRole($role)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized x3'], 401);
    }
}
