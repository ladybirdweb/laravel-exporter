<?php

namespace LWS\ExportActions;

use Illuminate\Support\Facades\Event;

use LWS\ExportActions\Events\QueueProcessed;
use LWS\ExportActions\Listeners\QueueResponse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class CustomEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        QueueProcessed::class => [
            QueueResponse::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
