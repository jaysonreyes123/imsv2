<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //
    public function session(){
        $user_id = Auth::id();
        Session::updateOrCreate(
            ['user_id' => $user_id],
            [
                'user_id' => $user_id,
                'last_activity' => Carbon::now()
            ] 
        );
    }
}
