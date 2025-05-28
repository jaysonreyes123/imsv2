<?php

namespace App\Listeners;

use App\Events\UserLogout;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class UserLogoutFired
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
    public function handle(UserLogout $event): void
    {
        //
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];
        $pusher = new Pusher(env('PUSHER_APP_KEY'),env('PUSHER_APP_SECRET'),env('PUSHER_APP_ID'), $options);
        $data = ["user_id" => $event->user->id, 'remember_token' => $event->user->remember_token];
        $pusher->trigger('user-logout', 'user-logout',$data);
    }
}
