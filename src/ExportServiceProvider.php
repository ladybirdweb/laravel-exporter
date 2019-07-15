<?php

namespace LWS\ExportActions;

use Queue;
use LWS\ExportActions\Jobs\WritePdf;
use Illuminate\Support\ServiceProvider;
use LWS\ExportActions\CustomEventServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__."/routes/web.php");
        $this->loadMigrationsFrom(__DIR__."/migrations/");

    }

    public function register()
    {
        $this->app->register(CustomEventServiceProvider::class);
    }
}