<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\RoleObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class Role
 * @package Drivezy\LaravelAccessManager\Models
 */
class Role extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_roles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ip_restrictions ()
    {
        return $this->hasMany(IPRestriction::class, 'source_id')->where('source_type', md5(self::class));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function role_assignments ()
    {
        return $this->hasMany(RoleAssignment::class);
    }

    /**
     * Load the observer rule against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new RoleObserver());
    }

}
