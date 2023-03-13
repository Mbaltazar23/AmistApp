<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $userRoles = UserRoles::where('user_id', $user->id)->first();

        foreach ($roles as $role) {
            if ($userRoles->hasRole($role)) {
                return $next($request);
            }
        }
        return response()->json(['error' => 'No autorizado'], 401);
    }

}