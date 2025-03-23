<?php

namespace App\Models;

use App\Http\Traits\Encryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Responder extends Model
{
    //
    use Encryption;
    public function getFirstnameAttribute($data)
    {
        return $this->decrypt_single('firstname',$this->attributes['firstname']);
    }
    public function getLastnameAttribute($data)
    {
        return $this->decrypt_single('lastname',$this->attributes['lastname']);
    }
    public function getContactNoAttribute($data)
    {
        return $this->decrypt_single('contact_no',$this->attributes['contact_no']);
    }
    public function responder_types(){
        return $this->hasOne(ResponderType::class,'id','responder_types');
    }
    public function statuses(){
        return $this->hasOne(Status::class,'id','statuses');
    }
    public function responder_statuses(){
        return $this->hasOne(ResponderStatus::class,'id','responder_statuses');
    }
    public function responder_types_(){
        return $this->hasOne(ResponderType::class,'id','responder_types');
    }
    public function statuses_(){
        return $this->hasOne(Status::class,'id','statuses');
    }
}
