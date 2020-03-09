<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;

class PermissionObserver extends BaseObserver
{
    protected $rules = [];

    protected $createRules = [
        'identifier' => 'required|unique:dz_permissions',
    ];
}
