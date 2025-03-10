<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    public function assigned_to(){
        return $this->hasOne(User::class,'id','assigned_to');
    }
}
