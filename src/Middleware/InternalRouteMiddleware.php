<?php

namespace Drivezy\LaravelAccessManager\Middleware;

use Closure;
use Drivezy\LaravelUtility\LaravelUtility;

/**
 * Class InternalRouteMiddleware
 * @package Drivezy\LaravelAccessManager\Middleware
 */
class InternalRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        //get the integration key from the property
        $pkey = LaravelUtility::getProperty('internal.server.key');
        $hKey = $request->header('access-key');

        if ( $hKey == $pkey && $pkey ) {
            return $next($request);
        } else
            return invalid_operation();
    }
}
