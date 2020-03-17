<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use App\User;
use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelAccessManager\ImpersonationManager;
use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Library\DateUtil;
use Drivezy\LaravelUtility\Library\EventQueueManager;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function validatePasswordLogin (Request $request)
    {
        $user = User::where('email', strtolower($request->email))->first();

        //if user is not found with the given login,
        // then send out the invalid credential message
        if ( !$user )
            return invalid_login();

        //check for max attempts
        $maxAttempts = LaravelUtility::getProperty('user.login.attempts', 3);
        if ( $user->attempts > $maxAttempts ) {
            EventQueueManager::setEvent('user.login.attempts.reset', $user->id, [
                'scheduled_start_time' => DateUtil::getFutureTime(5),
            ]);

            return failed_response('Too many attempts', 403);
        }

        //check for the login credentials of the user
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        if ( !Auth::attempt($credentials, true) ) {
            $user->attempts = $user->attempts + 1;
            $user->save();

            return invalid_login();
        }

        Auth::loginUsingId($user->id, true);

        return $this->getUserSessionDetails();
    }

    /**
     * @return JsonResponse
     */
    public function getUserSessionDetails ()
    {
        if ( !Auth::check() )
            return failed_response('Invalid Session');

        $user = Auth::user();

        $user->access_object = AccessManager::setUserObject();

        //assign the tokens of the user
        $user->proxy_token = AccessManager::generateTimeBasedUserToken();
        $user->parent_user = ImpersonationManager::getImpersonatingUserSession();

        return success_response($user);
    }


}
