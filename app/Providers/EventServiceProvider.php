<?php

namespace App\Providers;

use App\Events\TicketReserved;
use App\Listeners\SendInvoiceNotification;
use App\Notifications\SendEmailSpamDetected;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Honeypot\Events\SpamDetectedEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SpamDetectedEvent::class => [
            SendEmailSpamDetected::class,
        ],
        TicketReserved::class => [
            SendInvoiceNotification::class,
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
