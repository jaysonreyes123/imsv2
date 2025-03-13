<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    //
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['created_at'])->format('Y-m-d H:i:s');
    }
    public function incident_types(){
        return $this->hasOne(IncidentType::class,'id','incident_types');
    }
    public function incident_statuses(){
        return $this->hasOne(IncidentStatus::class,'id','incident_statuses');
    }
    public function incident_statuses_(){
        return $this->hasOne(IncidentStatus::class,'id','incident_statuses');
    }
    public function incident_priorities(){
        return $this->hasOne(IncidentPriority::class,'id','incident_priorities');
    }
    public function caller_types(){
        return $this->hasOne(CallerType::class,'id','caller_types');
    }
    public function contact_statuses(){
        return $this->hasOne(ContactStatus::class,'id','contact_statuses');
    }
}
