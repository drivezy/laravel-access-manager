<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelAccessManager\Models\PermissionAssignment;
use Drivezy\LaravelAccessManager\Models\RoleAssignment;
use Drivezy\LaravelAccessManager\Models\UserGroup;
use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserGroupMemberObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class UserGroupMemberObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @param Eloquent $model
     */
    public function created (Eloquent $model) {
        parent::created($model);

        //attach all the roles that is part of this group to the member
        self::attachRoleToUser($model);
        self::attachPermissionToUser($model);
    }

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model) {
        parent::deleted($model);

        //remove all the roles that was assigned because of this group
        self::removeRoleFromUser($model);
        self::removePermissionFromUser($model);
    }

    /**
     * @param Eloquent $model
     */
    private function attachRoleToUser (Eloquent $model) {
        $roles = RoleAssignment::where('source_type', UserGroup::class)->where('source_id', $model->id)->get();
        foreach ( $roles as $role ) {
            RoleAssignment::create([
                'source_type' => LaravelUtility::getUserModelFullQualifiedName(),
                'source_id'   => $model->user_id,
                'target_type' => UserGroup::class,
                'target_id'   => $model->user_group_id,
                'role_id'     => $role->role_id,
            ]);
        }
    }

    /**
     * @param Eloquent $model
     */
    private function attachPermissionToUser (Eloquent $model) {
        $permissions = PermissionAssignment::where('source_type', UserGroup::class)->where('source_id', $model->id)->get();
        foreach ( $permissions as $permission ) {
            PermissionAssignment::create([
                'source_type'   => LaravelUtility::getUserModelFullQualifiedName(),
                'source_id'     => $model->user_id,
                'target_type'   => UserGroup::class,
                'target_id'     => $model->user_group_id,
                'permission_id' => $permission->permission_id,
            ]);
        }
    }

    /**
     * @param Eloquent $model
     */
    private function removeRoleFromUser (Eloquent $model) {
        RoleAssignment::where('source_type', LaravelUtility::getUserModelFullQualifiedName())->where('source_id', $model->user_id)
            ->where('target_type', UserGroup::class)->where('target_id', $model->user_group_id)
            ->delete();
    }

    /**
     * @param Eloquent $model
     */
    private function removePermissionFromUser (Eloquent $model) {
        PermissionAssignment::where('source_type', LaravelUtility::getUserModelFullQualifiedName())->where('source_id', $model->user_id)
            ->where('target_type', UserGroup::class)->where('target_id', $model->user_group_id)
            ->delete();
    }
}