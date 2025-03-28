<?php

namespace App\Http\Traits;

use App\Models\CallLog;
use App\Models\PbxToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

trait PbxTrait
{
    //
    public function call_logs(){
        $output = $this->check_token_expiration();
        if(!$output['status']){
            return $output['data'];
        }
        $access_token = $output['data'];
        $pbx_token = PbxToken::where('name','yeastar')->first();
        $payload = [];
        if($pbx_token->last_scan == null){
            $payload['access_token'] = $access_token;
        }
        else{
            $payload['access_token'] = $access_token;
            $payload['start_time'] = $pbx_token->last_scan;
        }
        $request = $this->http_request()->get(env('YEASTAR_API')."/cdr/search",$payload);
        if($request['errcode'] == 0 && $request['total_number'] > 0){
            foreach($request['data'] as $call_log){
                $calllogs_dateandtime   = $call_log['time'];
                $calllogs_fromno        = $call_log['call_from_number'];
                $calllogs_tono          = $call_log['call_to_number'];
                $calllogs_callid        = $call_log['id'];
                $calllogs_duration      = isset($call_log['talk_duration']) ? $this->time_format($call_log['talk_duration']) : "Not Aswered";
                $calllogs_time_answered = isset($call_log['talk_duration']) ? $calllogs_dateandtime : "00:00:00";
                $calllogs_time_end      = isset($call_log['talk_duration']) ? Carbon::parse($calllogs_dateandtime)->addSeconds($call_log['talk_duration'])->format("m/d/Y H:i:s A") : "00:00:00";
                $call_logs_model = CallLog::where('date_and_time',$calllogs_dateandtime)
                ->where('from_no',$calllogs_fromno)
                ->where('to_no',$calllogs_tono)
                ->where('duration',$calllogs_duration)->get();
                if($call_logs_model->count() == 0){
                    $insert_model = new CallLog();
                    $insert_model->date_and_time    = $calllogs_dateandtime;
                    $insert_model->from_no          = $calllogs_fromno;
                    $insert_model->to_no            = $calllogs_tono;
                    $insert_model->duration         = $calllogs_duration;
                    $insert_model->call_id          = $calllogs_callid;
                    $insert_model->time_answered    = $calllogs_time_answered;
                    $insert_model->time_end         = $calllogs_time_end;
                    $insert_model->source           = 'YEASTAR';
                    $insert_model->save();
                }
            }
        }
    }
    public function check_token_expiration(){
        $result = [
            "status" => false,
            "data"   => ""
        ];
        $expired_token = false;
        $pbx_model = PbxToken::where('name','yeastar')->first();
        if(!$pbx_model){
            $pbx_model = new PbxToken();
            $pbx_model->name = "yeastar";
            $last_scan = null;
        }
        else{
            $current = Carbon::now();
            $last_scan = $current->format('m/d/Y h:i:s A');
            $expired_at = $pbx_model->expired_at;
            $diff = $current->diffInMinutes($expired_at);
            //token not expired return access token
            if($diff > 0){
                $result['status'] = true;
                $result['data'] = $pbx_model->access_token;
            }
            else{
                $expired_token = true;
            }
        }
        if($expired_token){
            $token = $this->get_token();
            if($token['errcode'] == 0){
                $pbx_model->access_token = $token['access_token'];
                $pbx_model->expired_at = Carbon::now()->addSecond($token['access_token_expire_time']);
                $pbx_model->last_scan = $last_scan;
                $pbx_model->save();

                $access_token = $token['access_token'];
                $result['status'] = true;
                $result['data'] = $access_token;
            }
            else{
                $result['status'] = false;
                $result['data'] = $token['errmsg'];
            }
        }
        else{
            $pbx_model->last_scan = $last_scan;
            $pbx_model->save();
        }
        return $result;
    }
    public function get_token(){
        $request = $this->http_request()
        ->post(env('YEASTAR_API')."/get_token",[
            "username" => env('YEASTAR_USERNAME'),
            "password" => env('YEASTAR_PASSWORD')
        ]);
        return $request;
    }
    public function http_request(){
        $request = Http::withoutVerifying()
        ->withHeaders([
            "Content-Type" => "application/json",
            "Access-Control-Allow-Origin" => "*"
        ])
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
