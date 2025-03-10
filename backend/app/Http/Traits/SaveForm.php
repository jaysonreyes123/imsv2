<?php

namespace App\Http\Traits;

use App\Http\Helpers\GenerateHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait SaveForm
{
    //
    protected function save($request,$model,$module,$id = ""){
        foreach($request as $field => $value){
            if($field == 'responder_types' && $module == 'incidents'){
                $model->$field = $value == null ? "[]" : json_encode($value) ;
            }
            else if($field == "incident_no" && $id == ""){
                list($prefix,$current_count) = GenerateHelpers::get('incidents');
                $model->$field = $prefix.$current_count;
            }
            else{
                $model->$field = $value;
            }
        }
        if($id == ""){
            $model->created_at = Carbon::now();
            $model->created_by = Auth::id();
            $model->updated_at = Carbon::now();
            $model->updated_by = Auth::id();
        }
        else{
            $model->updated_at = Carbon::now();
            $model->updated_by = Auth::id();
        }

        if($model->save()){
            if($module=='incidents'){
                $this->notification($model->id,$module);
            }
        }

        return $model;
    }       
    public function notification($id,$module){
        $module_detail  = ModuleHelpers::module_details_name($module);
        $model = new Notification();
        $model->module = $module_detail->id;
        $model->module_item_id = $id;
        $model->save();
    }
}
