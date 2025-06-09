<?php

namespace App\Http\Class;

use Illuminate\Support\Facades\Http;
use App\Http\Class\Yeastar;
use App\Models\Pbx;

class Call
{
    public function call_logs(){
        $pbx = Pbx::with('pbx_tokens_')->where('status',1)->first();
        $call = new Yeastar($pbx);
        $call->call_logs();
    }
    public function http_request(){
        $headers  = [
            "Content-Type" => "application/json",
            "Access-Control-Allow-Origin" => "*"
        ];
        $request = Http::withoutVerifying()
        ->withHeaders($headers)
		->withOptions(["verify"=>false]);
        return $request;
    }

    public function time_format($seconds){
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        $time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        return $time;
    }

}
