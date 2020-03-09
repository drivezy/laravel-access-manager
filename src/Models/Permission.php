<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\PermissionObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class Permission
 * @package Drivezy\LaravelAccessManager\Models
 */
class Permission extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_permissions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permission_assignments ()
    {
        return $this->hasMany(PermissionAssignment::class);
    }

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new PermissionObserver());
    }
}
