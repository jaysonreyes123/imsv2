<?php

namespace App\Http\Traits;

use App\Http\Helpers\GenerateHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Models\Contact;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

trait SaveForm
{
    //
    use Encryption;
    protected function save_contact($request){
        $contact_model = new Contact();
        $field_array = [
            "caller_types"      => "caller_types",
            "caller_firstname"  => "firstname",
            "caller_lastname"   => "lastname",
            "caller_contact"    => "mobile",
        ];
        $field_keys = array_keys($field_array);
        foreach($request as $fields => $value){
            if(in_array($fields,$field_keys)){
                $field = $field_array[$fields];
                $contact_model->$field = $this->encrypt($field,$value);
            }
        }
        $contact_model->save();
        return $contact_model->id;
    }
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
                if($value != ""){
                    $model->$field = $this->encrypt($field,$value);
                }
                // $model->$field = $value;
            }
        }
        if($module == 'incidents'){
            $contact_id = $this->save_contact($request);
            $model->contacts = $contact_id;
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
