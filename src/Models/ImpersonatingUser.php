<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ImpersonatingUser
 * @package Drivezy\LaravelAccessManager\Models
 */
class ImpersonatingUser extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_impersonating_users';

    /**
     * @return BelongsTo
     */
    public function parent_user ()
    {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName(), 'parent_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function impersonating_user ()
    {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName(), 'impersonating_user_id');
    }

    public function scopeActive ($query)
    {
        $query->whereNull('end_time');
    }
}
