<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preplan extends Model
{
    //
    public function risk_levels(){
        return $this->hasOne(RiskLevel::class,'id','risk_levels');
    }
    public function response_levels(){
        return $this->hasOne(ResponseLevel::class,'id','response_levels');
    }
}
