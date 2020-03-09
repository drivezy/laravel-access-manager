<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\SocialIdentifierObserver;
use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ImpersonatingUser
 * @package Drivezy\LaravelAccessManager\Models
 */
class SocialIdentifier extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_social_identifiers';

    /**
     *
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new SocialIdentifierObserver());
    }

    /**
     * @return BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName());
    }
}
