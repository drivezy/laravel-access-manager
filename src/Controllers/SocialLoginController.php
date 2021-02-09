<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginViaToken (Request $request)
    {
        if ( Auth::login() ) return success_response(AccessManager::getUserSessionDetails());

        $socialUser = Socialite::driver($request->provider)->userFromToken($request->token);

        return $this->processUserObject($socialUser);
    }

    public function login (Request $request, $provider)
    {
        return Socialite::driver($provider)
            ->redirect();
    }

    public function validateCallback (Request $request, $provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        if ( !$socialUser->getEmail() ) return;

        return $this->processUserObject($socialUser);
    }

    /**
     * @param $socialUser
     * @return mixed
     */
    private function createUser ($socialUser)
    {
        $user = LaravelUtility::getUserModelFullQualifiedName();
        return $user::create([
            'email'        => $socialUser->getEmail,
            'display_name' => $socialUser->getName,
            'password'     => Hash::make($socialUser->getId),
        ]);
    }

    private function processUserObject ($socialUser)
    {
        if ( !$socialUser->getEmail() ) return invalid_login('Access token is invalid');

        $user = LaravelUtility::getUserModelFullQualifiedName();
        $user = $user::where('email', $socialUser->getEmail())->first();
        if ( !$user )
            $user = $this->createUser();

        Auth::loginUsingId($user->id, true);

        return success_response(AccessManager::getUserSessionDetails());
    }
}
