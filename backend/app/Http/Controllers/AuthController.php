<?php

namespace App\Http\Controllers;

use App\Events\UserLogout;
use App\Http\Constants\AuthConstants;
use App\Http\Traits\HttpResponse;
use App\Mail\AuthOtp;
use App\Mail\ForgotPassword;
use App\Models\LoginHistory;
use App\Models\OtpToken;
use App\Models\PasswordResetToken;
use App\Models\Responder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    //
    use HttpResponse; 

    public function verified($email){
        $remember_token = Str::random();
        $user = User::where('email',$email)->first();
        $user->attempts = 0;
        $user->remember_token = $remember_token;
        $user->save();
        $user->tokens()->delete();
        // UserLogout::dispatch($user->id,$remember_token);
        event(new UserLogout($user));
        $success = $user->createToken('ims')->plainTextToken;
        $this->login_history($user->id,1);
        return $this->response($success,AuthConstants::LOGIN);
    }
    public function send_otp($user){
        try {
            OtpToken::where("email",$user->email)->delete();
            $token = strtoupper(Str::random(6));
            Mail::to($user->email)->send(new AuthOtp($token,$user));
            $model = new OtpToken;
            $model->email = $user->email;
            $model->token = $token;
            $model->save();
            return $this->response([
                "type" => "otp",
                "email" => $user->email
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function verify(Request $request){
        $token = $request->token;
        $email = $request->email;
        $verify_model = OtpToken::where('email',$email,)->where("token",$token)->first();
        if(!$verify_model){
           return $this->response([],"invalid Token",422);
        }
        $verify_model->delete();
        return $this->verified($email);
    }
    public function otp(){
        $user_details = auth()->user();
        $ipaddress = \Request::ip();
        $new_ipaddress = LoginHistory::select('ipaddress')->where('ipaddress',$ipaddress)->where('user_id',$user_details->id)->get();
        //check if first login
        if($new_ipaddress->count() == 0 && $user_details->id != 1 && $user_details->id != 2){
            return $this->send_otp($user_details);
        }
        else{
            return $this->verified($user_details->email);
        }          
    }
    public function login(Request $request)  {
        if(auth()->attempt($request->all())){
            return $this->otp();
        }
        $attempts = User::where('email',$request->email)->first();
        if($attempts){
            if($attempts->attempts <= 2){
                $attempts->attempts = $attempts->attempts + 1;
                $attempts->save();
            }
            else{
                return $this->send_otp(auth()->user());
            }
        }   
        return $this->response([],AuthConstants::VALIDATION,422);
    }
    public function logout() : JsonResponse {
        $user = auth()->user();
        $user->tokens()->delete();
        $this->login_history($user->id,2);
        return $this->response([],AuthConstants::LOGOUT);
    }
    public function forgotPassword($email){
        $message = "";
        try {
            $email_checker = User::where('email',$email)->first();
            if(!$email_checker) {
                $message= "Email not existing";
            }
            else{  
                $token = Str::random(64);
                Mail::to($email)->send(new ForgotPassword($token,$email,'user'));
                $model = PasswordResetToken::firstOrNew(['email' => $email]);
                $model->email = $email;
                $model->token = $token;
                $model->target = 'user';
                $model->created_at = Carbon::now();
                $model->save();
                $message = "success";
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();

        }
        return $this->response($message);
    }
    public function resetpassword(Request $request)
    {
        $request->validate([
            'password' => 
            [
                'required',
                'min:8',
            ]
        ]);
        $message = "";
        $status = 200;
        $model = PasswordResetToken::where('token', $request->token)->where('target',$request->option)->first();
        if ($model == null) {
            $message = "Invalid Token! Please try again";
            $status = 422;
        } else {
            if($request->option == 'user'){
                User::where('email', $model->email)->update(['password' => Hash::make($request->password)]);
            }
            else{
                Responder::where('email_address',$model->email)->update(["password" => $request->password]);
            }
            $model->delete();
            $message = "success";
        }
        return $this->response([],$message,$status);
    }
    public function login_history($user_id,$login){
        $model = $login == 1 ? new LoginHistory() : LoginHistory::where('user_id',$user_id)->orderByDesc('signin')->first();
        $model->user_id = $user_id;
        $model->ipaddress = \Request::ip();
        $model->status = $login;
        if($login == 1){
            $model->signin = Carbon::now();
        }
        else{
            $model->signout = Carbon::now();
        }
        $model->user_agent = \Request::userAgent();
        $model->save();
    }
}
