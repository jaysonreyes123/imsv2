<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    //
    public $fillable = ['name','location','link','coordinates'];
}
