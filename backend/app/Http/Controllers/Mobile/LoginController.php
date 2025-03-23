<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\MobileTokenTrait;
use App\Models\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    //
    use HttpResponse,MobileTokenTrait;
    public function data($data){
        return [
            "responderid"   => $data->id,
            "firstname"     => $data->firstname,
            "middlename"    => "",
            "lastname"      => $data->lastname,
            "department"    => $data->responder_types_->label,
            "email"         => $data->email_address,
            "contactno"     => $data->contact_no,
            "status"        => $data->statuses_->label
        ];
    }
    public function login(Request $request){
        $data = json_decode($request->getContent(),true);
        $email = $data['data']['email'];
        $password = $data['data']['password'];
        $responder = Responder::with('responder_types_','statuses_')->where('email_address',$email)->where("password",$password)->first();
        if(!$responder){
            return response()->json([
                "status" => "Error" , 
                "result" => "The email or password you entered is incorrect."
            ]);
        }
        else{
            //inactve
            if($responder->statuses == 2){
                return response()->json([
                    "status"    => "Error",
                    "result"    => "Your account is pending verification. Please contact administrator for more details."
                ]);
            }
            $token = Str::random();
            $responder->token = $token;
            $responder->save(); 
            Log::info("LOGIN: ",["responder id" => $responder->id]);
            return response()->json([
                "status" => "Success" , 
                "result" =>  $this->data($responder),
                "token"  =>  $token
            ]);
        }
    }
    public function logout(Request $request){
        $data  = json_decode($request->getContent(),true);
        $token = $data['data']['token'];
        $token_data = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
        $token_data->token = null;
        $token_data->save();
        Log::info("LOGOUT: ",["responder id" => $token_data->id]);
        return response()->json([
            "status" => "Success",
            "result" => "Succesfully logout."   
        ]);
    }
}
