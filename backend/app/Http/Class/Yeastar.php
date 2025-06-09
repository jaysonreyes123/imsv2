<?php

namespace App\Http\Class;

use App\Models\CallLog;
use App\Models\PbxToken;
use Carbon\Carbon;

class Yeastar extends Call
{
    protected $pbx;
    public function __construct($pbx)
    {
        $this->pbx = $pbx;   
    }
    public function call_logs(){
        $token = $this->check_token_expiration();
        if(!$token['status']){
            return $token;
        }
        $current_pbx = $this->pbx;
        $access_token = $token['data'];
        $pbx_token =  PbxToken::where('pbx_id',$current_pbx->id)->first();
        $payload = [];
        if($pbx_token->last_scan == null){
            $payload['access_token'] = $access_token;
        }
        else{
            $payload['access_token'] = $access_token;
            $payload['start_time'] = $pbx_token->last_scan;
        }
        $request = $this->http_request()
        ->get($current_pbx->api."/cdr/search",$payload);
        // encounter error
        if($request['errcode'] != 0){
            return $request;
        }
        if($request['total_number'] > 0){

            foreach($request['data'] as $call_log){
                $calllogs_dateandtime   = $call_log['time'];
                $calllogs_fromno        = $call_log['call_from_number'];
                $calllogs_tono          = $call_log['call_to_number'];
                $calllogs_callid        = $call_log['id'];
                $calllogs_duration      = isset($call_log['talk_duration']) ? $this->time_format($call_log['talk_duration']) : "Not Aswered";
                $calllogs_time_answered = isset($call_log['talk_duration']) ? $calllogs_dateandtime : "00:00:00";
                $calllogs_time_end      = isset($call_log['talk_duration']) ? Carbon::parse($calllogs_dateandtime)
                ->addSeconds($call_log['talk_duration'])
                ->format("m/d/Y H:i:s A") : "00:00:00";

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
    public function auth_token(){
        $url = $this->pbx->api;
        $payload = [
            "username" => $this->pbx->username,
            "password" => $this->pbx->password
        ];
        $request = $this->http_request()
        ->post($url."/get_token",$payload);
        return $request;
    }
    public function check_token_expiration(){
        $result = [
            "status" => false,
            "data"   => ""
        ];
        $expired_token = false;
        $current_pbx = $this->pbx;
        $pbx_model = PbxToken::where('pbx_id',$current_pbx->id)->first();
        $last_scan = null;
        if(!$pbx_model){
            $expired_token = true;
            $pbx_model = new PbxToken();
            $pbx_model->pbx_id = $current_pbx->id;
            $pbx_model->last_scan = $last_scan;
        }
        else{
            $expired_at = $pbx_model->expired_at;
            $current = Carbon::now();
            $last_scan = $current->format('m/d/Y H:i:s');
            $diff = $current->diffInMinutes($expired_at);
            //token not expired return access token
            if($diff > 0){
                $expired_token = false;
                $result['status'] = true;
                $result['data'] = $pbx_model->access_token;
            }
            else{
                $expired_token = true;
            }
        }
        if($expired_token){
            $token = $this->auth_token();
    
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
}
