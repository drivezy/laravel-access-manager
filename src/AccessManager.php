<?php

namespace Drivezy\LaravelAccessManager;

use Drivezy\LaravelAccessManager\Models\PermissionAssignment;
use Drivezy\LaravelAccessManager\Models\RoleAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class AccessManager
 * @package Drivezy\LaravelAccessManager
 */
class AccessManager {
    /**
     * @var string
     */
    private static $identifier = 'user-access-object-';

    /**
     * @param $role
     * @return bool
     */
    public static function hasRole ($role) {
        $userObject = self::getUserObject();

        //super user should always get access to all the resources in the system
        if ( in_array(1, $userObject->roles) ) return true;

        $roles = is_array($role) ? $role : [$role];

        //if the access is given to public for the same, allow the same
        if(in_array(2, $roles)) return true;

        foreach ( $roles as $role ) {
            if ( is_numeric($role) ) {
                if ( in_array($role, $userObject->roles) ) return true;
            } elseif ( is_string($role) ) {
                if ( in_array($role, $userObject->roleIdentifiers) ) return true;
            }
        }

        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public static function hasPermission ($permission) {
        $userObject = self::getUserObject();

        //super user should always get access to all the resources in the system
        if ( in_array(1, $userObject->roles) ) return true;

        $permissions = is_array($permission) ? $permission : [$permission];

        foreach ( $permissions as $permission ) {
            if ( is_numeric($permission) ) {
                if ( in_array($permission, $userObject->permissions) ) return true;
            } elseif ( is_string($permission) ) {
                if ( in_array($permission, $userObject->permissionIdentifiers) ) return true;
            }
        }

        return false;
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getUserObject ($id = null) {
        $id = $id ? : Auth::id();

        if ( Cache::has(self::$identifier . $id) )
            return Cache::get(self::$identifier . $id);

        return self::setUserObject($id);
    }

    /**
     * @param null $id
     * @return array
     */
    public static function setUserObject ($id = null) {
        $id = $id ? : Auth::id();
        $roles = $roleIdentifiers = $permissions = $permissionIdentifiers = [];

        //get the roles that are assigned to the user
        $records = RoleAssignment::with('role')->where('source_type', 'User')->where('source_id', $id)->get();
        foreach ( $records as $record ) {
            if ( in_array($record->role_id, $roles) ) continue;

            array_push($roles, $record->role_id);
            array_push($roleIdentifiers, $record->role->identifier);
        }

        //get the permissions assigned to the user
        $records = PermissionAssignment::with('permission')->where('source_type', 'User')->where('source_id', $id)->get();
        foreach ( $records as $record ) {
            if ( in_array($record->permission_id, $permissions) ) continue;

            array_push($permissions, $record->permission_id);
            array_push($permissionIdentifiers, $record->permission->identifier);
        }

        //create the access object against the user
        $accessObject = (object) [
            'roles'                 => $roles,
            'roleIdentifiers'       => $roleIdentifiers,
            'permissions'           => $permissions,
            'permissionIdentifiers' => $permissionIdentifiers,
        ];

        Cache::forever(self::$identifier . $id, $accessObject);

        return $accessObject;
    }

}