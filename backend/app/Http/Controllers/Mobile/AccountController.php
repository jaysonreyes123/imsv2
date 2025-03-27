<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Traits\Encryption;
use App\Http\Traits\MobileTokenTrait;
use App\Mail\ForgotPassword;
use App\Models\PasswordResetToken;
use App\Models\Responder;
use App\Models\ResponderMessage;
use App\Models\ResponderType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class AccountController extends Controller
{
    //
    use MobileTokenTrait,Encryption;

    public function updateprofile(Request $request){
        $data           = json_decode($request->getContent(),true);
        $firstname      = $data['data']['firstname'];
        $lastname       = $data['data']['lastname'];
        $middlename     = $data['data']['middlename'];
        $department     = $data['data']['department'];
        $email          = $data['data']['email'];       
        $mobileno       = $data['data']['mobileno'];
        $token          = $data['data']['token'];
        $password       = isset($data['data']['password']) ? $data['data']['password'] : "";
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
        $request = [
            "email" => $email
        ];
        $validate = Validator::make($request,[
            'email' => [Rule::unique('responders','email_address')->ignore($token_data->id),'regex:/(.+)@(.+)\.(.+)/i'],
        ]);
        // email exist
        if($validate->fails()){
            return response()->json([
                "status"    => "Error",
                "result"   => "The email is already exists. Please try a different email address."
            ]);
        }
        $token_data->firstname      = $this->encrypt('firstname',$firstname);
        $token_data->lastname       = $this->encrypt('lastname',$lastname);
        $token_data->contact_no     = $this->encrypt('contact_no',$mobileno);
        $token_data->email_address  = $email;

        $responder_type = ResponderType::where('label',$department)->first();
        $token_data->responder_types = $responder_type->id;
        if($password != ""){
            $token_data->password = $password;
        }
        $token_data->save();
        Log::info("Profile Update: ",["responder_id" => $token_data->id]);
        return response()->json([
            "status"    => "Success",
            "result"    => $token_data
        ]);

    }
    public function ondutystatus(Request $request){
        $data               = json_decode($request->getContent(),true);
        $ondutystatus       = $data['data']['ondutystatus'];
        $responder_id       = $data['data']['responder_id'];
        $token              = $data['data']['token'];
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
        // 2 is inactive
        $token_data->responder_statuses = $ondutystatus == 0 ? 2 : 1;
        $token_data->save();
        Log::info("On Duty Status Update:",["responder_id" => $token_data->id,"status" => $token_data->responder_statuses]);
    }

    public function location(Request $request){
        $data               = json_decode($request->getContent(),true);
        $latitude           = $data['data']['latitude'];
        $longitude          = $data['data']['longitude'];
        $responder_id       = $data['data']['responder_id'];
        $token              = $data['data']['token'];
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
    }
    public function message(Request $request){
        $responder_id = $request->responder_id;
        $message_model = ResponderMessage::select('id','responder_id','message','created_at as createddatetime')
        ->where('responder_id',$responder_id)->get();
        return response()->json([
            "status"    => "Success",
            "result"    => $message_model
        ]);
    }   
    public function forgotpassword(Request $request){
        $email = $request->email;
        $responder = Responder::where('email_address',$email)->first();
        if(!$responder){
            return response()->json([
                "status"    => "Error",
                "result"    => "Email record not found."
            ]);
        }
        if($responder->statuses == 2){
            return response()->json([
                "status"    => "Error",
                "result"    => "Your account is pending verification. Please contact administrator for more details."
            ]);
        }

        try {
            $token = Str::random(64);
            Mail::to($email)->send(new ForgotPassword($token,$email,'responder'));
            $model = PasswordResetToken::firstOrNew(['email' => $email]);
            $model->email = $email;
            $model->token = $token;
            $model->target = 'responder';
            $model->created_at = Carbon::now();
            $model->save();

            return response()->json([
                "status"    => "Success",
                "result"    => "Email password reset sent. Please check your email."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"    => "Error",
                "result"    => "Something Wrong."
            ]);
        }
    }
    public function register(Request $request){
        Log::info($request->getContent());
        $data           = json_decode($request->getContent(),true);
        $firstname      = $data['data']['firstname'];
        $lastname       = $data['data']['lastname'];
        $middlename     = $data['data']['middlename'];
        $department     = $data['data']['department'];
        $email          = $data['data']['email'];
        $password       = $data['data']['password'];
        $mobileno       = $data['data']['mobileno'];

        $request = [
            "email" => $email
        ];
        $validate = Validator::make($request,[
            'email' => [Rule::unique('responders','email_address'),'regex:/(.+)@(.+)\.(.+)/i'],
        ]);
        // email exist
        if($validate->fails()){
            return response()->json([
                "status"    => "Error",
                "result"   => "The email is already exists. Please try a different email address."
            ]);
        }
        $responder_type = ResponderType::where('label',$department)->first();
        $model = new Responder();
        $model->firstname = $this->encrypt('firstname',$firstname);
        $model->lastname = $this->encrypt('lastname',$lastname);
        $model->contact_no = $this->encrypt('contact_no',$mobileno);
        $model->password = $password;
        $model->email_address = $this->encrypt('email_address',$email);
        $model->responder_statuses = 2;
        $model->responder_types = $responder_type->id;
        $model->statuses = 2;
        $model->source = 'mobile';
        $model->save();
        return response()->json([
            "status"    => "Success",
            "result"   => "Successfully registered. Your account is pending verification. Please contact administrator for more details."
        ]);
    }
}
