<?php

namespace Drivezy\LaravelAccessManager\Middleware;

use Closure;
use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelAccessManager\RouteManager;
use Illuminate\Http\Request;

class ValidateRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        if ( RouteManager::validateRouteAccess($request) )
            return $next($request);

        return AccessManager::unauthorizedAccess();
    }
}
