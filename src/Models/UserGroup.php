<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\AccessManager;
use Drivezy\LaravelAccessManager\Observers\UserGroupObserver;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UserGroup
 * @package Drivezy\LaravelAccessManager\Models
 */
class UserGroup extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_user_groups';

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new UserGroupObserver());
    }

    /**
     * @return BelongsTo
     */
    public function manager ()
    {
        return $this->belongsTo(AccessManager::getUserClass());
    }

    /**
     * @return HasMany
     */
    public function members ()
    {
        return $this->hasMany(UserGroupMember::class);
    }

    /**
     * @return HasMany
     */
    public function roles ()
    {
        return $this->hasMany(RoleAssignment::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     * @return HasMany
     */
    public function permissions ()
    {
        return $this->hasMany(PermissionAssignment::class, 'source_id')->where('source_type', md5(self::class));
    }
}
