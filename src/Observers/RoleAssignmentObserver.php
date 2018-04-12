<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelAccessManager\Models\RoleAssignment;
use Drivezy\LaravelAccessManager\Models\UserGroupMember;
use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RoleAssignmentObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class RoleAssignmentObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'source_type' => 'required',
        'source_id'   => 'required',
        'role_id'     => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function created (Eloquent $model) {
        parent::created($model);

        if ( $model->source_type == 'UserGroup' )
            self::attachRoleToGroup($model);
    }

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model) {
        parent::deleted($model);

        //remove all correlated items
        self::removeAssociatedRoles($model);
    }

    /**
     * @param Eloquent $model
     */
    private function attachRoleToGroup (Eloquent $model) {
        $members = UserGroupMember::where('user_group_id', $model->source_id)->get();
        foreach ( $members as $member ) {
            RoleAssignment::create([
                'source_type' => 'User',
                'source_id'   => $member->user_id,
                'target_type' => 'UserGroup',
                'target_id'   => $model->source_id,
                'role_id'     => $model->role_id,
            ]);
        }
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    private function removeAssociatedRoles (Eloquent $model) {
        if ( $model->source_type == 'User' ) return false;

        RoleAssignment::where('target_type', $model->source_type)->where('target_id', $model->source_id)
            ->where('role_id', $model->role_id)
            ->delete();
    }
}