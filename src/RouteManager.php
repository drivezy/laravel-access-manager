<?php

namespace Drivezy\LaravelAccessManager;

use Drivezy\LaravelAccessManager\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class RouteManager
 * @package Drivezy\LaravelAccessManager
 */
class RouteManager {
    /**
     * @var string
     */
    private static $identifier = 'access-route-';

    /**
     * @param Request $request
     * @return bool
     */
    public static function validateRouteAccess (Request $request) {
        $uri = preg_replace('/\/\d*$/', '', $request->getRequestUri());
        $hash = md5($request->method() . '-' . $uri);

        $route = self::getRouteDetails($hash);

        if ( $route )
            return self::isRouteAllowed($route);

        return true;
    }

    /**
     * Added support to add all routes defined in the system
     */
    public static function logAllRoutes () {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        foreach ( $routes as $route ) {
            $url = '/' . preg_replace('/\/{.*?\}|\s*/', '', $route->uri);
            $hash = md5($route->methods[0] . '-' . $url);

            //create record only when its a new record
            $record = Route::where('route_hash', $hash)->first();
            if ( $record ) continue;

            Route::create([
                'uri'        => $url,
                'method'     => $route->methods[0],
                'route_hash' => $hash,
            ]);
        }
    }

    /**
     * @param $hash
     * @return bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    private static function getRouteDetails ($hash) {
        $route = Cache::get(self::$identifier . $hash, false);
        if ( $route )
            return $route;

        $route = Route::with(['roles', 'permissions'])->where('route_hash', $hash)->first();
        if ( $route ) {
            Cache::forever(self::$identifier . $hash, $route);

            return $route;
        }

        return false;
    }

    /**
     * @param $route
     * @return bool
     */
    private static function isRouteAllowed ($route) {
        $requiredRoles = $requiredPermissions = [];

        //if no permission or role is setup for the system, then authorize the request
        if ( !( sizeof($route->roles) || sizeof($route->permissions) ) )
            return true;

        //validate if the route roles match the request
        foreach ( $route->roles as $role )
            array_push($requiredRoles, $role->role_id);

        if ( AccessManager::hasRole($requiredRoles) )
            return true;

        //validate if route permissions match the request
        foreach ( $route->permissions as $permission )
            array_push($requiredPermissions, $permission->permission_id);

        if ( AccessManager::hasPermission($requiredPermissions) )
            return true;

        return false;
    }
}