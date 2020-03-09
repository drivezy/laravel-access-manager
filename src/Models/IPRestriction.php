<?php

namespace Drivezy\LaravelAccessManager\Models;

use Drivezy\LaravelAccessManager\Observers\IPRestrictionObserver;
use Drivezy\LaravelUtility\Models\BaseModel;

/**
 * Class IPRestriction
 * @package Drivezy\LaravelAccessManager\Models
 */
class IPRestriction extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'dz_ip_restrictions';

    /**
     * Override the observer against the model
     */
    public static function boot ()
    {
        parent::boot();
        self::observe(new IPRestrictionObserver());
    }
}
