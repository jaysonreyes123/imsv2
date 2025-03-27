<?php

namespace App\Models;

use App\Http\Traits\Encryption;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
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
    public function getMobileAttribute($data)
    {
        return $this->decrypt_single('mobile',$this->attributes['mobile']);
    }
    public function caller_types(){
        return $this->hasOne(CallerType::class,'id','caller_types');
    }
}
