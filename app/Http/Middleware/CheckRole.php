<?php

namespace App\Http\Middleware;

use App\Models\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRoles = UserRoles::where('user_id', $user->id)->first();

        foreach ($roles as $role) {
            if ($userRoles->hasRole($role)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'No tienes los permisos necesarios para acceder a esta página.');
    }
}
