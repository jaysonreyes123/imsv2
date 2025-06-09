<?php

namespace App\Models;

use App\Http\Traits\Encryption;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
    use Encryption;
    public function getContactNoOneAttribute($data)
    {
        return $this->decrypt_single('contact_no_one',$this->attributes['contact_no_one']);
    }
    public function getContactNoTwoAttribute($data)
    {
        return $this->decrypt_single('contact_no_two',$this->attributes['contact_no_two']);
    }
}
