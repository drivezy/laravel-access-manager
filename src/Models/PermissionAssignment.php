<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\PermissionAssignmentObserver;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PermissionAssignment
 * @package Drivezy\LaravelAccessManager\Models
 */
class PermissionAssignment extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_permission_assignments';

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new PermissionAssignmentObserver());
    }

    /**
     * @return BelongsTo
     */
    public function permission ()
    {
        return $this->belongsTo(Permission::class);
    }
}
