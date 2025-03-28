<?php

namespace App\Http\Traits;

use App\Models\PbxToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

trait PbxTrait
{
    //
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
        $current = Carbon::now();
        $expired_at = $pbx_model->expired_at;
        $diff = $current->diff($expired_at);
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
