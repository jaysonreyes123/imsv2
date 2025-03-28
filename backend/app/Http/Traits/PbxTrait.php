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
        $access_token = $this->check_token_expiration();
        $start_time = Carbon::now()->format("m/d/Y")."  12:00:00 AM";
        $request = $this->http_request()->get(env('YEASTAR_API')."/cdr/search",[
            "access_token" => $access_token,
            "start_time"   => $start_time,
        ]);
        if($request['errcode'] == 0){
            foreach($request['data'] as $call_log){
                $calllogs_tks_dateandtime   = $call_log->time;
                $calllogs_tks_fromno        = $call_log->call_from_number;
                $calllogs_tks_tono          = $call_log->call_to_number;
                $calllogs_tks_duration      = $call_log->duration;
                $call_logs_model = CallLog::where('date_and_time',$calllogs_tks_dateandtime)
                ->where('from_no',$calllogs_tks_fromno)
                ->where('to_no',$calllogs_tks_tono)
                ->where('duration',$calllogs_tks_duration)->get();
                if($call_logs_model->count() == 0){
                    $insert_model = new CallLog();
                    $insert_model->date_and_time = $calllogs_tks_dateandtime;
                    $insert_model->from_no = $calllogs_tks_fromno;
                    $insert_model->to_no = $calllogs_tks_tono;
                    $insert_model->duration = $calllogs_tks_duration;
                    $insert_model->source = '3CX';
                    $insert_model->save();
                }
            }
        }
    }
    public function check_token_expiration(){
        $pbx_model = PbxToken::where('name','yeastar')->first();
        $access_token = "";
        if(!$pbx_model){
            $token = $this->get_token();
            $model = new PbxToken();
            $model->name = 'yeastar';
            $model->access_token = $token['access_token'];
            $model->expired_at = Carbon::now()->addSecond($token['access_token_expire_time']);
            $model->save();
            $access_token = $token['access_token'];
        }
        else{
            $current = Carbon::now();
            $expired_at = $pbx_model->expired_at;
            $diff = $current->diffInMinutes($expired_at);
            if($diff < 0){
                $token = $this->get_token();
                $pbx_model->access_token = $token['aaccess_token'];
                $pbx_model->expired_at = Carbon::now()->addSecond($token['access_token_expire_time']);
                $pbx_model->save();
                $access_token = $token['access_token'];
            }
            else{
                $access_token = $pbx_model->access_token;
            }
        }
        return $access_token;
    }
    public function get_token(){
        $request = $this->http_request()
        ->post(env('YEASTAR_API')."/get_token",[
            "username" => env('YEASTAR_USERNAME'),
            "password" => env('YEASTAR_PASSWORD')
        ]);
        if($request['errcode'] == 0){
            return $request;
        }
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
}
