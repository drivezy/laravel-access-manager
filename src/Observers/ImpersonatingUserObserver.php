<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

/**
 * Class ImpersonatingUserObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class ImpersonatingUserObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'session_identifier'    => 'required',
        'parent_user_id'        => 'required',
        'impersonating_user_id' => 'required',
        'start_time'            => 'required',
    ];
}
