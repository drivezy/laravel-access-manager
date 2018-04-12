<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelAccessManager\Models\PermissionAssignment;
use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

class PermissionAssignmentObserver extends BaseObserver {
    protected $rules = [
        'source_type'   => 'required',
        'source_id'     => 'required',
        'permission_id' => 'required',
    ];

    public function created (Eloquent $model) {
        parent::created($model);

        if ( $model->source_type == 'UserGroup' )
            self::attachPermissionToGroup($model);
    }

    public function deleted (Eloquent $model) {
        parent::deleted($model);

        self::removeAssociatedPermissions($model);
    }

    /**
     * @param Eloquent $model
     */
    private function attachPermissionToGroup (Eloquent $model) {
        $members = UserGroupMember::where('user_group_id', $model->source_id)->get();
        foreach ( $members as $member ) {
            PermissionAssignment::create([
                'source_type'   => 'User',
                'source_id'     => $member->user_id,
                'target_type'   => 'UserGroup',
                'target_id'     => $model->source_id,
                'permission_id' => $model->permission_id,
            ]);
        }
    }

    /**
     * @param Eloquent $model
     * @return bool
     */
    private function removeAssociatedPermissions (Eloquent $model) {
        if ( $model->source_type == 'User' ) return false;

        PermissionAssignment::where('target_type', $model->source_type)->where('target_id', $model->source_id)
            ->where('permission_id', $model->permission_id)
            ->delete();
    }
}