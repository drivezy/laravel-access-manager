<?php

namespace App\Http\Middleware;

use Closure;
use Drivezy\LaravelAccessManager\RouteManager;

class ValidateRouteAccess {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle ($request, Closure $next) {
        if ( RouteManager::validateRouteAccess($request) )
            return $next($request);
    }
}
