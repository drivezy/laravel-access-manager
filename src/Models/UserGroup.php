<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\UserGroupObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class UserGroup
 * @package Drivezy\LaravelAccessManager\Models
 */
class UserGroup extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_user_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members () {
        return $this->hasMany(UserGroupMember::class);
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new UserGroupObserver());
    }
}