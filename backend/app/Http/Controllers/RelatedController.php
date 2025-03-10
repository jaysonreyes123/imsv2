<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\RelatedEntries;
use App\Http\Traits\SaveForm;
use App\Http\Traits\Table;
use App\Models\RelatedEntry;
use Illuminate\Http\Request;

class RelatedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use Table,HttpResponse,SaveForm,RelatedEntries;
    public function index(Request $request)
    {
        //
        $model = ModuleHelpers::list($request->related_module);
        return $this->response($this->related_list_function($model,$request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $model = ModuleHelpers::new($request->related_module);
        $_ = $this->save($request->except('module','id','related_id','related_module'),$model,$request->related_module);
        $this->save_related_entries($request->id,$request->module,$_->id,$request->related_module);
        ActivityLogsHelpers::log($request->id,$request->module,$status = 4,related_module:$request->related_module,related_item_id:$_->id);
        return $this->response([]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $model = ModuleHelpers::find($id,$request->related_module);
        $_ = $this->save($request->except('module','id','related_id','related_module'),$model,$request->related_module);
        $this->save_related_entries($request->id,$request->module,$_->id,$request->related_module);
        return $this->response([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function delete($module,$related_module,$module_id,$related_module_id){
    
        $module_ = ModuleHelpers::module_details_name($module)->id;
        $related_module_ = ModuleHelpers::module_details_name($related_module)->id;
        $model = RelatedEntry::where("module",$module_)
        ->where("related_module",$related_module_)
        ->where("module_id",$module_id)
        ->where("related_id",$related_module_id)
        ->delete();
        ActivityLogsHelpers::log($module_id,$module,$status = 5,related_module:$related_module,related_item_id:$related_module_id);
        return $this->response($model);
        // if($model){
        //     RelatedEntriesAll::where("module",$module_)
        //     ->where("related_module",$related_module_)
        //     ->where("module_id",$module_id)
        //     ->where("related_id",$related_module_id)
        //     ->where('relation',1)
        //     ->delete();
        //     ActivityLogsHelpers::log($module_id,$module_,status:5,related_module:$related_module_,related_item_id:$related_module_id);
        //     return $this->response($model);
        // }
    }
    public function save_selected_row(Request $request){
        foreach($request->selected_row as $related_id){
            $this->save_related_entries($request->id,$request->module,$related_id,$request->related_module);
            ActivityLogsHelpers::log($request->id,$request->module,$status = 4,related_module:$request->related_module,related_item_id:$related_id);
        }
    }
}
