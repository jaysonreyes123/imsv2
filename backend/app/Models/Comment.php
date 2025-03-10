<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    public function users(){
        return $this->hasOne(User::class,'id','comment_by');
    }
}
