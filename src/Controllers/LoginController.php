<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelAccessManager\ImpersonationManager;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @package Drivezy\LaravelAccessManager\Controllers
 */
class LoginController extends Controller {

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSessionDetails () {
        if ( !Auth::check() )
            return failed_response('Invalid Session');

        $user = Auth::user();

        $user->access_object = AccessManager::setUserObject();
        $user->parent_user = ImpersonationManager::getImpersonatingUserSession();

        return success_response($user);
    }
}