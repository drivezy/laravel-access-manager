<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

class RoleObserver extends BaseObserver {
    protected $rules = [];

    protected $createRules = [
        'identifier' => 'required|unique',
    ];
}