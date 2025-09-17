<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HrdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_user === 'HRD') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}