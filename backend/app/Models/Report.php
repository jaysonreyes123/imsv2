<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    
    protected $casts = ['related_module' => 'array'];
    public function report_columns(){
        return $this->hasMany(ReportColumn::class,'report_id');
    }
    public function report_conditions(){
        return $this->hasMany(ReportCondition::class,'report_id');
    }
    public function report_charts(){
        return $this->hasOne(ReportChart::class,'report_id');
    }
}
