<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

/**
 * Class IPRestrictionObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class IPRestrictionObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'source_type' => 'required',
        'source_id'   => 'required',
    ];
}
