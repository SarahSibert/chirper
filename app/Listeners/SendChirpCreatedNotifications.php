<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewChirp;
use App\Events\ChirpCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChirpCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChirpCreated $event): void
    {
        // check the .env to see if we want to send the notifications or not.
        // currently no mail handling is set up on the live server so this is false
        if (config('SEND_CHIRP_EMAILS')) {
            foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
                $user->notify(new NewChirp($event->chirp));
            }
        }        
    }
}
