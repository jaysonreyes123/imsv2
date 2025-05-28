<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //
    public function session(){
        $user_id = Auth::id();
        $user = User::find($user_id);
        if($user->status == 0){
            $user->status = 1;
            $user->save();
        }
        Session::updateOrCreate(
            ['user_id' => $user_id],
            [
                'user_id' => $user_id,
                'last_activity' => Carbon::now()
            ] 
        );
    }
}
