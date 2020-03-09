<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelAccessManager\Models\RoleAssignment;
use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RoleObserver
 * @package Drivezy\LaravelAccessManager\Observers
 */
class RoleObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'name'       => 'required',
        'identifier' => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function deleted (Eloquent $model)
    {
        RoleAssignment::where('role_id', $model->id)->delete();
        parent::deleted($model);
    }
}