<?php

namespace LWS\ExportActions;

use Illuminate\Support\ServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations/');
    }

    public function register()
    {
        $this->app->register(CustomEventServiceProvider::class);
    }
}
