<?php

namespace App\Listeners;

use App\Notifications\UserWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())->get()
            ->each->notify(new UserWereMentioned($event->thread, $event->reply));
    }
}
