<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelAccessManager\ImpersonationManager;
use Drivezy\LaravelUtility\Library\DeviceTokenManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @package Drivezy\LaravelAccessManager\Controllers
 */
class LoginController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSessionDetails (Request $request)
    {
        //capture device token
        DeviceTokenManager::captureDeviceToken($request);

        if ( !Auth::check() )
            return failed_response('Invalid Session');

        $user = Auth::user();

        $user->access_object = AccessManager::setUserObject();
        $user->parent_user = ImpersonationManager::getImpersonatingUserSession();

        return success_response($user);
    }
}