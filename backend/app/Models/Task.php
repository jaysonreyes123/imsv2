<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    public function tasks_statuses(){
        return $this->hasOne(TaskStatus::class,'id','tasks_statuses');
    }
}
