<?php

namespace Drivezy\LaravelAccessManager;

use Illuminate\Support\ServiceProvider;

class LaravelAccessManagerServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot () {
        //load routes defined out here
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        //load migrations as part of this package
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register () {

    }
}