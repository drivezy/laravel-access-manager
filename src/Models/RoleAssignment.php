<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\RoleAssignmentObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class RoleAssignment
 * @package Drivezy\LaravelAccessManager\Models
 */
class RoleAssignment extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'dz_role_assignments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role ()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return $this
     */
    public function user_group ()
    {
        return $this->belongsTo(UserGroup::class, 'target_id')->where('source_type', md5(self::class));
    }

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new RoleAssignmentObserver());
    }

}
