<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\UserGroupMemberObserver;
use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserGroupMember
 * @package Drivezy\LaravelAccessManager\Models
 */
class UserGroupMember extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_user_group_members';

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new UserGroupMemberObserver());
    }

    /**
     * @return BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName());
    }

    /**
     * @return BelongsTo
     */
    public function user_group ()
    {
        return $this->belongsTo(UserGroup::class);
    }
}
