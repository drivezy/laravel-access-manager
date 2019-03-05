<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

/**
 * Class SocialIdentifierObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class SocialIdentifierObserver extends BaseObserver {
    /**
     * @var array
     */
    protected $rules = [
        'identifier' => 'required',
        'user_id'    => 'required',
    ];
}
