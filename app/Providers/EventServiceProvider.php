<?php

namespace App\Providers;

use App\Events\ThreadHasNewReply;
use App\Listeners\NotifyMentionedUsers;
use App\Listeners\NotifySubscibers;
use App\Listeners\SendConfirmationEmail;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
            SendConfirmationEmail::class,
        ],
        ThreadHasNewReply::class => [
            NotifyMentionedUsers::class,
            NotifySubscibers::class
        ]
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
