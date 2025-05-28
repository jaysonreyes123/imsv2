<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Http\Resources\NotificationResource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class NotificationEventFire
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
    public function handle(NotificationEvent $event): void
    {
        //

        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];
        $pusher = new Pusher(env('PUSHER_APP_KEY'),env('PUSHER_APP_SECRET'),env('PUSHER_APP_ID'), $options);
        $notification = new NotificationResource($event->notification);
        $data = ["notification" => $notification];
        $pusher->trigger('notification-event', 'notification-event',$data);
    }
}
