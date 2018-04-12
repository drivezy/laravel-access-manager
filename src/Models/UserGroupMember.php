<?php

namespace Drivezy\LaravelAccessManager\Models;

use App\User;
use Drivezy\LaravelAccessManager\Observers\UserGroupMemberObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class UserGroupMember
 * @package Drivezy\LaravelAccessManager\Models
 */
class UserGroupMember extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_user_group_members';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_group () {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new UserGroupMemberObserver());
    }
}