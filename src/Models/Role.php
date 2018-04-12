<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\RoleObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class Role
 * @package Drivezy\LaravelAccessManager\Models
 */
class Role extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_roles';

    /**
     * Load the observer rule against the model
     */
    public static function boot () {
        parent::boot();
        self::observe(new RoleObserver());
    }

}