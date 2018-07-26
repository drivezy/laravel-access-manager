<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\RouteObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class Route
 * @package Drivezy\LaravelAccessManager\Models
 */
class Route extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_routes';

    /**
     * @return $this
     */
    public function roles () {
        return $this->hasMany(RoleAssignment::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     * @return $this
     */
    public function permissions () {
        return $this->hasMany(PermissionAssignment::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new RouteObserver());
    }
}