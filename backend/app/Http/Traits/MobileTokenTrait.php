<?php

namespace App\Http\Traits;

use App\Models\Responder;

trait MobileTokenTrait
{
    //
    protected function token($token){
        $data = null;
        $responder = Responder::where('token',$token)->first();
        if($responder){
            $data = $responder;
        }
        return $data;
    }
}
