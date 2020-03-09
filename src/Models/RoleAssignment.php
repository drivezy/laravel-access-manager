<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\RoleAssignmentObserver;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new RoleAssignmentObserver());
    }

    /**
     * @return BelongsTo
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

}
