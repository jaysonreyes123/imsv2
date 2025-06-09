<?php

namespace App\Http\Controllers;

use App\Http\Class\Call;
use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Resources\ModuleResource;
use App\Http\Traits\Encryption;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\PbxTrait;
use App\Http\Traits\SaveForm;
use App\Models\Contact;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse,SaveForm,Encryption;
    public function get_modules(){
        $modules = Module::all();
        return $this->response($modules);
    }
    public function index(Request $request)
    {
        // $model = Incident::with('incident_types','incident_priorities','incident_statuses');
        if($request->module == 'call_logs'){
            $call = new Call();
            $call->call_logs();
        }
        $model = ModuleHelpers::list($request->module);
        if($request->search['value'] != ""){
            foreach($request->search['search_field'] as $search_field){
                $parse = explode(".",$search_field);
                $search = $request->search['value'];
                //dropdown table
                if(count($parse) == 2){
                    $col = $parse[0];
                    $relation_model = DB::table($parse[0])->select('id')->where('label','like',"$search%");
                    $model = $model->orWhereIn("$col",$relation_model);
                }   
                else{
                    $model = $model->orWhere($search_field,"like",$this->encrypt($search_field,$search)."%");
                }
            }
        }
        if(!empty($request->filter)){
            foreach($request->filter as $filter){
                if($filter['field'] != ""){
                    $model = $model->where($filter['field'],"like",$this->encrypt($filter['field'],$filter['value'])."%");
                }
            }
        }
        if($request->module != 'insight_reports'){
            $model = $model->where('deleted',0);
        }
        $model = $model->orderByDesc('updated_at');
        $model = $model->paginate(15);
        return $this->response($model);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $model = ModuleHelpers::new($request->module);
        $_ = $this->save($request->except('module','id'),$model,$request->module);
        ActivityLogsHelpers::log($_->id,$request->module,1,$request->all());
        return $this->response($_->id);
    }

    /**
     * Display the specified resource.
     */
    public function multiselect($field,$values){
        $newvalue = [];
        if(!empty($values)){
            foreach($values as $value){
                $options = DB::table($field)->where("id",$value)->first();
                $newvalue[] = $options->label;
            }
        }
        return implode(",",$newvalue);
  
    }
    public function show(string $module,string $id)
    {
        //
        $model = ModuleHelpers::list($module);
        $model = $model->find($id);
        //multiselect 
        if($module == 'incidents'){
            $columns = ['responder_types'];
            foreach($columns as $column){
                $model->$column = $this->multiselect($column,json_decode($model->$column,true));
            }
        }
        return $this->response(new ModuleResource($model));
    }
    public function edit(string $module,string $id){
        $model = ModuleHelpers::find($id,$module);
        // $model = $this->decrypt($model);
        return $this->response($model);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $model = ModuleHelpers::find($id,$request->module);
        $original = $model->getOriginal();
        $_ = $this->save($request->except('module','id'),$model,$request->module,$id);
        ActivityLogsHelpers::log($_->id,$request->module,2,$_->getChanges(),$original);
        return $this->response($_->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request){
        $ids = $request->id;
        $module = $request->module;
        foreach($ids as $id){
            $model = ModuleHelpers::find($id,$module);
            $model->deleted = 1;
            $model->save();
        }
        return $this->response($model);
    }
    public function checknumber(string $phone){
        $phone = $this->encrypt('mobile',$phone);
        $model = Contact::where('mobile',$phone)->first();
        $status = 1;
        if($model){
            $status = 2;
        }
        return $this->response($status);
    }
}
