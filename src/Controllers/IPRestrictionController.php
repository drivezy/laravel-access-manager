<?php

namespace Drivezy\LaravelAccessManager\Controllers;

use Drivezy\LaravelAccessManager\Models\IPRestriction;
use Drivezy\LaravelRecordManager\Controllers\RecordController;

/**
 * Class IPRestrictionController
 * @package Drivezy\LaravelAccessManager\Controllers
 */
class IPRestrictionController extends RecordController {
    /**
     * @var string
     */
    protected $model = IPRestriction::class;
}