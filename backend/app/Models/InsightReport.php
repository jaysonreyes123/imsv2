<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsightReport extends Model
{
    //
    public function getTypeAttribute(){
        if($this->attributes['type'] == 1){
            return "Daily";
        }
        else if($this->attributes['type'] == 2){
            return "Weekly";
        }
        else if($this->attributes['type'] == 3){
            return "Montly";
        }
    }
}
