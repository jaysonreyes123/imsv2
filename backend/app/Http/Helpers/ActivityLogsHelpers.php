<?php

namespace App\Http\Helpers;

use App\Models\ActivityDetail;
use App\Models\ActivityMain;
use App\Models\ActivityRelated;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class ActivityLogsHelpers{
    protected static $not_allow = ['id','module','created_at','updated_at','created_by','updated_by','source','deleted','email_verified_at','remember_token','password','user_id','contacts'];
    public static function log($itemid,$module,$status,$fields = [],$old_field = [],$related_module = 0,$related_item_id = 0){
        $logs = new ActivityMain();
        $logs->itemid = $itemid;
        $logs->module = self::get_module_id($module);
        $logs->status = $status;
        $logs->whodid = Auth::id();
        $logs->save();

        if($status == 1 || $status == 6){
            self::create($logs->id,$fields);
        }
        else if($status == 2){
            self::update($logs->id,$fields,$old_field);
        }
        else if($status == 4 || $status == 5 ){
            self::link($logs->id,$related_module,$related_item_id);
        }
        
    }

    public static function link($activityid,$related_module,$itemid){
        $link_model = new ActivityRelated();
        $link_model->activity_log_id = $activityid;
        $link_model->related_module = self::get_module_id($related_module);
        $link_model->related_item_id = $itemid;
        $link_model->save();
    }
    public static function update($logsid,$fields,$oldfield){
        if(!empty($fields)){
            foreach($fields as $field => $value){
                if(!in_array($field,self::$not_allow)){
                    $logs_details = new ActivityDetail();
                    $logs_details->activity_log_id = $logsid;
                    $logs_details->field = $field;
                    $logs_details->oldvalue = $oldfield[$field];
                    $logs_details->newvalue = $value;
                    $logs_details->save(); 
                }
            }
        }
    }
    public static function create($logsid,$fields){
        foreach($fields as $field => $value){
            if(!in_array($field,self::$not_allow)){
                if($value != "" || $value != null){
                    $logs_details = new ActivityDetail();
                    $logs_details->activity_log_id = $logsid;
                    $logs_details->field = $field;
                    $logs_details->newvalue = $value;
                    $logs_details->save();   
                }
            }
        }
    }
    public static function get_module_id($module){
        $module_model = Module::where('name',$module)->first();
        return $module_model->id;
    }
}