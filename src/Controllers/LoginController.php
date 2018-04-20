<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelUtility\Library\DateUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @package Drivezy\LaravelAccessManager\Controllers
 */
class LoginController extends Controller {

    /**
     * @param Request $request
     * @return mixed
     */
    public function login (Request $request) {
        $user = User::where('email', $request->email)->first();

        if ( !$user ) return self::invalidLogin();

        if ( $user->attempts > 3 )
            return Response::json(['success' => false, 'reason' => 'Too many attempts invalid attempts'], 401);

        if ( !Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], true) ) {
            $user->attempts = $user->attempts + 1;
            $user->save();

            return self::invalidLogin();
        }

        $user->attempts = 0;
        $user->last_login_time = DateUtil::getDateTime();
        $user->save();

        return Response::json(['success' => true, 'response' => AccessManager::getUserObject($user->id)]);
    }

    /**
     * @return mixed
     */
    public function loginCheck () {
        if ( Auth::check() )
            return Response::json(['success' => true, 'response' => AccessManager::getUserObject()]);

        return self::invalidLogin();
    }

    /**
     * @return mixed
     */
    private function invalidLogin () {
        return Response::json(['success' => false, 'response' => 'Insufficient Privileges'], 401);
    }
}