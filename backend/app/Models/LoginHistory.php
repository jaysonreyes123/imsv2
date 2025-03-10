<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    //
    public function users_(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
