<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Resources\ModuleResource;
use App\Http\Traits\HttpResponse;
use App\Models\ActivityDetail;
use App\Models\ActivityMain;
use App\Models\User;
use App\Models\UserPrivilege;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse;
    public $not_allow = ['created_by','created_at','updated_at','updated_by','password'];
    public function user_details() : JsonResponse {
        $model = User::with(['user_roles','user_privileges'])->find(auth()->user()->id);
        return $this->response($model);
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return $this->response($this->save($request));
        
    }
    public function create_logs($itemid,$module,$fields){
        $logs = new ActivityMain();
        $logs->itemid = $itemid;
        $logs->module = ModuleHelpers::module_details_name($module)->id;
        $logs->status = 1;
        $logs->whodid = Auth::id();
        
        if($logs->save()){
            foreach($fields as $field => $value){
                if(in_array($field,array('password','updated_at'))){
                    if($value != "" || $value != null){
                        $logs_details = new ActivityDetail();
                        $logs_details->activity_log_id = $logs->id;
                        $logs_details->field = $field;
                        $logs_details->newvalue = $value;
                        $logs_details->save();   
                    }
                }
            }
        }
    }
    public function update_logs($itemid,$module,$newfield,$oldfield){
        $logs = new ActivityMain();
        $logs->itemid = $itemid;
        $logs->module = ModuleHelpers::module_details_name($module)->id;
        $logs->status = 2;
        $logs->whodid = Auth::id();
    
        if($logs->save()){
            if(!empty($newfield)){
                foreach($newfield as $field => $value){
                    if(!in_array($field,$this->not_allow)){
                        if($value != "" || $value != null){
                            $logs_details = new ActivityDetail();
                            $logs_details->activity_log_id = $logs->id;
                            $logs_details->field = $field;
                            $logs_details->oldvalue = $oldfield[$field];
                            $logs_details->newvalue = $value;
                            $logs_details->save(); 
                        }
                    }
                }
            }
        }
    }
    public function save($request,$id = ""){
        $request->validate([
            'email' => ['email',Rule::unique('users')->ignore($id),'regex:/(.+)@(.+)\.(.+)/i'],
        ]);
        $model = $id == "" ? new User() : User::find($id);
        foreach($request->all() as $field => $value){
            $parse_field = explode(".",$field);
            if(count($parse_field) == 1){
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
        $original = $model->getOriginal();
        if($model->save()){
            if($id == ""){
                $this->create_logs($model->id,'users',$request->all());
            }
            else{
                $get_changes = $model->getChanges();
                $this->update_logs($model->id,'users',$get_changes,$original);
            }
            
            foreach($request->all() as $field => $value){
                $parse_module = explode(".",$field);
                if(count($parse_module) == 2){
                    $module_details = ModuleHelpers::module_details_name($parse_module[1]);
                    $user_privileges_model = $id == "" ? new UserPrivilege() : UserPrivilege::where('user_id',$model->id)->where('module',$module_details->id)->first();    
                    $user_privileges_model->user_id = $model->id;
                    $user_privileges_model->module = $module_details->id;
                    $user_privileges_model->status = $value;
                    $user_privileges_model->save();
                }
            }
        }
        return $model->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $model = User::with('user_roles','user_privileges')->find($id);
        return $this->response(new ModuleResource($model));
    }
    public function edit(string $id){
        $model = User::with('user_roles','user_privileges')->find($id);
        return $this->response($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return $this->response($this->save($request,$id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function edit_profile(Request $request,string $action,string $id){
        $request->validate([
            'email' => ['email',Rule::unique('users')->ignore($id),'regex:/(.+)@(.+)\.(.+)/i'],
        ]);
        if($action == 'personal-information'){
            $model = User::find($id);
            $model->firstname = $request->firstname;
            $model->lastname = $request->lastname;
            $model->user_roles = $request->user_roles;
            $model->email = $request->email;
            $model->save();
        }
        else if($action == 'user-privileges'){
            foreach($request->all() as $field => $value){
                $parse_module = explode(".",$field);
                if(count($parse_module) == 2){
                    $module_details = ModuleHelpers::module_details_name($parse_module[1]);
                    $user_privileges_model =  UserPrivilege::where('user_id',$id)->where('module',$module_details->id)->first();
                    $user_privileges_model->status = $value;
                    $user_privileges_model->save();
                }
            }
        }
        else if($action == 'change-password'){
            $model = User::find($id);
            $model->password = Hash::make($request->password);
            $model->save();
        }
        return $this->user_details();
    }
}
