<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class checkSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $call_takers = User::with('sessions')->where('user_roles',3)->where('status',1)->get();
        foreach($call_takers as $call_taker){
            if(!is_null($call_taker->sessions)){
                $current_datetime = Carbon::now();
                $session_time = Carbon::parse($call_taker->sessions->last_activity)->addMinutes((int)env('SESSION_LIFETIME'));
                $diff = $current_datetime->diffInMinutes($session_time);
                Log::info("check session",["diff" => $diff,"session time" => $session_time,"current" => $current_datetime]);
                if($diff < 0 ){
                    $this->fire_event($call_taker->id);
                    User::where('id',$call_taker->id)->update(['status' => 0]);
                }
            }
        }
    }

    public function fire_event($id){
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ];
        $pusher = new Pusher(env('PUSHER_APP_KEY'),env('PUSHER_APP_SECRET'),env('PUSHER_APP_ID'), $options);
        $data = ["user_id" => $id];
        $pusher->trigger('session-expired-event', 'session-expired-event',$data);
    }
}
