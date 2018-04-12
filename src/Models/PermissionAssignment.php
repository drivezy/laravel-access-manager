<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\PermissionAssignmentObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class PermissionAssignment
 * @package Drivezy\LaravelAccessManager\Models
 */
class PermissionAssignment extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_permission_assignments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission () {
        return $this->belongsTo(Permission::class);
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new PermissionAssignmentObserver());
    }
}