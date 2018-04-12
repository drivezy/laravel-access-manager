<?php

namespace Drivezy\LaravelAccessManager\Observers;

use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

class RouteObserver extends BaseObserver {
    protected $rules = [
        'uri'        => 'required',
        'method'     => 'required',
        'route_hash' => 'required',
    ];

    public function saving (Eloquent $model) {
        $model->route_hash = md5($model->method . '-' . $model->uri);

        return parent::saving($model);
    }
}