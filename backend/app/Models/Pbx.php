<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pbx extends Model
{
    //
    public function pbx_tokens_(){
        return $this->hasOne(PbxToken::class,'pbx_id',"id");
    }
}
