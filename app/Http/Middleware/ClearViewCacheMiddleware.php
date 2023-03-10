<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;

class ClearViewCacheMiddleware
{
    public function handle($request, Closure $next)
    {
        Artisan::call('view:clear');

        return $next($request);
    }
}
