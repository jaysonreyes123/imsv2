<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //

    public function resources_statuses(){
        return $this->hasOne(ResourcesStatus::class,'id','resources_statuses');
    }
    public function resources_types(){
        return $this->hasOne(ResourcesType::class,'id','resources_types');
    }
    public function resources_categories(){
        return $this->hasOne(ResourcesCategory::class,'id','resources_categories');
    }
}
