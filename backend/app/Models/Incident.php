<?php

namespace App\Models;

use App\Http\Traits\Encryption;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    //
    use Encryption;
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['created_at'])->format('Y-m-d H:i:s');
    }
    public function getCallerFirstnameAttribute($value){
        return $this->decrypt_single('caller_firstname',$this->attributes['caller_firstname']);
    }
    public function getCallerLastnameAttribute($value){
        return $this->decrypt_single('caller_lastname',$this->attributes['caller_lastname']);
    }
    public function getCallerContactAttribute($value){
        return $this->decrypt_single('caller_contact',$this->attributes['caller_contact']);
    }

    public function incident_types(){
        return $this->hasOne(IncidentType::class,'id','incident_types');
    }
    public function incident_types_(){
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
    public function incident_priorities_(){
        return $this->hasOne(IncidentPriority::class,'id','incident_priorities');
    }
    public function caller_types(){
        return $this->hasOne(CallerType::class,'id','caller_types');
    }
    public function contact_statuses(){
        return $this->hasOne(ContactStatus::class,'id','contact_statuses');
    }
    public function contacts(){
        return $this->hasOne(Contact::class,'id','contacts');
    }
}
