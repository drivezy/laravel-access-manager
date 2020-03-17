<?php

namespace Drivezy\LaravelAccessManager;

use Drivezy\LaravelAccessManager\Models\Route;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Class RouteManager
 * @package Drivezy\LaravelAccessManager
 */
class RouteManager
{
    /**
     * @var string
     */
    private static $identifier = 'access-route-';

    /**
     * @param Request $request
     * @return bool
     */
    public static function validateRouteAccess (Request $request)
    {
        //check all the request headers for authentication mechanism
        self::checkRequestHeaders($request);

        $uri = preg_replace('/\/\d*$/', '', $request->getRequestUri());
        $hash = md5($request->method() . '-' . $uri);

        $route = self::getRouteDetails($hash);

        //check if ip whitelisting is allowed for the particular request
        if ( !self::isIPAllowed() ) return false;

        if ( $route )
            return self::isRouteAllowed($route);

        return true;
    }

    /**
     * @param Request $request
     * @return Authenticatable|void
     */
    private static function checkRequestHeaders (Request $request)
    {
        if ( Auth::check() ) return;

        if ( $request->hasHeader('access_token') ) {
            $userId = AccessManager::verifyTimeBasedUserToken($request->header('access_token'));
            if ( $userId )
                return Auth::loginUsingId($userId);
        }

        if ( $request->has('access_token') ) {
            $userId = AccessManager::verifyTimeBasedUserToken($request->access_token);
            if ( $userId )
                return Auth::loginUsingId($userId);
        }

        //check for the basic authentication
        if ( $request->hasHeader('PHP_AUTH_USER') || $request->hasHeader('HTTP_AUTHORIZATION') ) {
            Auth::basic('email');
        }
    }

    /**
     * @param $hash
     * @return bool|Model|null|object|static
     */
    private static function getRouteDetails ($hash)
    {
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
     * @return bool
     */
    public static function isIPAllowed ()
    {
        $userObject = AccessManager::getUserObject();

        //if no restriction then allow the record
        if ( !sizeof($userObject->restricted_ips) ) return true;

        //get the ip of the request
        $headers = getallheaders();
        $ip = isset($headers['X-Forwarded-For']) ? $headers['X-Forwarded-For'] : '127.0.0.1';

        //see if user ip falls within a given cidr
        foreach ( $userObject->restricted_ips as $item ) {
            if ( IpUtils::checkIp4($ip, $item) )
                return true;
        }

        return false;
    }

    /**
     * @param $route
     * @return bool
     */
    private static function isRouteAllowed ($route)
    {
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

    /**
     * Added support to add all routes defined in the system
     */
    public static function logAllRoutes ()
    {
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
}
