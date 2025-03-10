<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responder extends Model
{
    //
    public function responder_types(){
        return $this->hasOne(ResponderType::class,'id','responder_types');
    }
}
