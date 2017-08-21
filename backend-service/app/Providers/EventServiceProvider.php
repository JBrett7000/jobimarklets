<?php

namespace App\Providers;

use App\Events\UserEvent;
use App\Listeners\UserListerner;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\UserEvent' => [
            'App\Listeners\UserListerner',
        ]
    ];
}
