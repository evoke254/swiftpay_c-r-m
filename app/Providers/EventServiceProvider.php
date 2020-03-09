<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\StockUpdateEvent' => [
            'App\Listeners\StockUpdateEventListener',
        ],
        'App\Events\PosStockUpdateEvent' => [
            'App\Listeners\PosStockUpdateEventListener',
        ],
    ];


    public function boot()
    {
        parent::boot();

        //
    }
}
