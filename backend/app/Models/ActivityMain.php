<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ActivityMain extends Model
{
    //
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['created_at'])->format('F d Y H:i:s');
    }
    public function activity_details(){
        return $this->hasMany(ActivityDetail::class,'activity_log_id','id');
    }
    public function activity_relations(){
        return $this->hasOne(ActivityRelated::class,'activity_log_id','id');
    }
    public function whodid_(){
        return $this->hasOne(User::class,'id','whodid');
    }
}
