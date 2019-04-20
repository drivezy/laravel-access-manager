<?php

namespace Drivezy\LaravelAccessManager\Middleware;

use Closure;
use Drivezy\LaravelAccessManager\AccessManager;
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

        return Response::json(['success' => false, 'response' => 'Route access not allowed'], 403);
    }
}
