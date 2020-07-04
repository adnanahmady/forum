<?php

namespace App\Listeners;

class NotifySubscibers
{
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle($event)
    {
        $event->thread->subscribes
            ->where('user_id', '!=', $event->reply->user_id)
            ->each->notify($event->reply);
    }
}
