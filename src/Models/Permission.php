<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\PermissionObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class Permission
 * @package Drivezy\LaravelAccessManager\Models
 */
class Permission extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_permissions';

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new PermissionObserver());
    }
}