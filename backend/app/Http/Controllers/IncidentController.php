<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\SaveForm;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use SaveForm,HttpResponse;
    public function index(Request $request)
    {
        //
        $model = Incident::with('incident_types','incident_priorities','incident_statuses');
        if(!empty($request->filter)){
            foreach($request->filter as $filter){
                if($filter['field'] != ""){
                    $model = $model->where($filter['field'],"like",$filter['value']."%");
                }
            }
        }
        $model = $model->where('deleted',0);
        $model = $model->paginate(1);
        return $this->response($model);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $model = ModuleHelpers::new($request->module);
        $_ = $this->save($request->except('module','id'),$model);
        ActivityLogsHelpers::log($_->id,$request->module,1,$request->all());
        return $this->response([$_->id]);
    }   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return $this->response(Incident::with('incident_types','incident_priorities','incident_statuses')->find($id));
       
    }
    public function edit(string $id){
        $model = ModuleHelpers::find($id,'incindets');
        return $this->response($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $model = ModuleHelpers::find($request->id,$request->module);
        $original = $model->getOriginal();
        $_ = $this->save($request->except('module','id'),$model);
        ActivityLogsHelpers::log($_->id,$request->module,2,$_->getChanges(),$original);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function delete(Request $request)
    {
        //
        $ids = $request->id;
        $model = Incident::whereIn("id",$ids)->update(["deleted" => 1]);
        return $this->response($model);
    }
}
