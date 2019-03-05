<?php
namespace Drivezy\LaravelAccessManager\Libraries;


use Drivezy\LaravelAccessManager\Models\SocialIdentifier;
use Drivezy\LaravelUtility\Library\DateUtil;
use Illuminate\Support\Facades\Auth;
use P2PApp\User;

/**
 * Class SocialManager
 * @package Drivezy\LaravelAccessManager\Libraries
 */
class SocialManager {
    private static function findUserByIdentifier ($identifier, $source) {
        $user = User::whereNotNull('identifier')->where('identifier', $identifier)
            ->first();
        if ( $user ) {
            return self::loginUser($user);
        }

        $otherSocialIdentifier = SocialIdentifier::where('identifier', $identifier)
            ->where('source', $source)
            ->first();

        if ( !$otherSocialIdentifier ) {
            return false;
        }

        $user = User::find($otherSocialIdentifier->user_id);
        if ( $user ) {
            return self::loginUser($user);
        }

        return false;
    }

    /**
     * @param $user
     * @return mixed
     */
    public static function loginUser ($user) {
        Auth::loginUsingId($user->id, true);
        $user->last_login_time = DateUtil::getDateTime();
        $user->save();

        return $user;
    }

    /**
     * @param $source
     * @param $accessToken
     * @return mixed
     */
    public static function getDataArrFromSource ($source, $accessToken) {
        if ( $source == 'google' ) {
            $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $accessToken;
        } elseif ( $source == 'fb' ) {
            $url = 'https://graph.facebook.com/me?fields=id,name,email,link,first_name,last_name,gender&access_token=' . $accessToken;
        }

        $arr = json_decode(RemoteRequest::getRequest($url));
        if ( isset($arr->error->code) || !isset($arr->id) ) {
            return false;
        }

        return $arr;
    }

    /**
     * @param $userId
     * @param $identifier
     * @param $source
     * @return SocialIdentifier
     */
    private static function createSocialIdentifier ($userId, $identifier, $source) {
        $obj = new SocialIdentifier();
        $obj->user_id = $userId;
        $obj->identifier = $identifier;
        $obj->source = $source;
        $obj->save();

        return $obj;
    }

    /**
     * @param $email
     * @param $identifier
     * @param $source
     * @return bool|mixed
     */
    private static function findUserByEmail ($email, $identifier, $source) {
        $user = User::whereNotNull('email')->where('email', $email)->first();
        if ( !$user ) {
            return false;
        }

        $user = self::loginUser($user);
        self::createSocialIdentifier($user->id, $identifier, $source);

        return $user;
    }

    /**
     * @param $data
     * @param $source
     * @return bool|mixed
     */
    public static function getUser ($data, $source) {
        $user = self::findUserByIdentifier($data->id, $source);
        if ( $user )
            return $user;

        if ( !isset($data->email) )
            return false;

        $user = self::findUserByEmail($data->email, $data->id, $source);

        return $user ? : false;
    }

    /**
     * @param $firstName
     * @param $lastName
     * @return string
     */
    public static function getDisplayName ($firstName, $lastName) {
        if ( $firstName && !$lastName )
            $name = $firstName;
        elseif ( !$firstName && $lastName )
            $name = $lastName;
        elseif ( $firstName && $lastName )
            $name = $firstName . ' ' . $lastName;
        else
            $name = 'Rider';

        return ucwords(strtolower($name));
    }
}
