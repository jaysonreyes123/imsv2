<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    public function module_(){
        return $this->belongsTo(Module::class,'module','id');
    }
}
