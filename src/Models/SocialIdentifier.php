<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\SocialIdentifierObserver;
use Drivezy\LaravelUtility\LaravelUtility;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class ImpersonatingUser
 * @package Drivezy\LaravelAccessManager\Models
 */
class SocialIdentifier extends BaseModel {
    /**
     * @var string
     */
    protected $table = 'dz_social_identifiers';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(LaravelUtility::getUserModelFullQualifiedName());
    }

    /**
     *
     */
    public static function boot () {
        parent::boot();
        self::observe(new SocialIdentifierObserver());
    }
}
