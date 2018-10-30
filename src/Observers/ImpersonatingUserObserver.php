<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

class ImpersonatingUserObserver extends BaseObserver {
    protected $rules = [
        'session_identifier'    => 'required',
        'parent_user_id'        => 'required',
        'impersonating_user_id' => 'required',
        'start_time'            => 'required',
    ];
}