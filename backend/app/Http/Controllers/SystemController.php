<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ModuleHelpers;
use App\Http\Resources\ActivityLogsResource;
use App\Http\Resources\LoginHistoryResource;
use App\Http\Resources\NotificationResource;
use App\Models\ActivityMain;
use App\Models\LoginHistory;
use App\Models\Notification;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    //
    public function login_history(Request $request){
        $id = $request->id;
        $model = LoginHistory::query();
        if($id != "all"){
            $model = $model->where('user_id',$id);
        }
        $model = $model->orderByDesc('created_at')->paginate(20);
        return LoginHistoryResource::collection($model);
    }
    public function activity_logs($module,$itemid){
        $module_details = ModuleHelpers::module_details_name($module);
        $activity_model = ActivityMain::with(['activity_details','activity_relations'])
        ->where('itemid',$itemid)
        ->where('module',$module_details->id)
        ->orderByDesc('created_at')
        ->paginate(10);
        return ActivityLogsResource::collection($activity_model);    
    }
    public function notification(){
        $notification_model = Notification::with('module_')->orderByDesc('created_at')->limit(10)->get();
        return NotificationResource::collection($notification_model);
    }
}
