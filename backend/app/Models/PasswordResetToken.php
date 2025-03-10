<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    //
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'email';
    protected $fillable = ['email', 'token', 'create_at'];
}