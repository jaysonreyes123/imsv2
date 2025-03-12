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
            'cluster' => 'ap1',
            'useTLS' => true
        ];
        $pusher = new Pusher('9e44dc07ce874ae1fd2a', '07768390c595398ed2f9', '1665256', $options);
        $data = ["user_id" => $event->user->id, 'remember_token' => $event->user->remember_token];
        $pusher->trigger('user-logout', 'user-logout',$data);
    }
}
