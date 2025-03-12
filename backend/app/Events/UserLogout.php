<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogout implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user_id;
    public $remember_token;
    public function __construct($user_id,$remember_token)
    {
        //
        $this->user_id = $user_id;
        $this->remember_token = $remember_token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastAs(){
        return "user-logout";
    }
    public function broadcastOn(): array
    {
        return [
            new Channel("user-logout"),
        ];
    }
    public function broadcastWith()  {
        return [
            "user_id" =>$this->user_id,
            "remember_token" => $this->remember_token
        ];
    }
}
