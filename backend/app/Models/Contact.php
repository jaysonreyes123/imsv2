<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    public function caller_types(){
        return $this->hasOne(CallerType::class,'id','caller_types');
    }
}
