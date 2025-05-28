<?php

namespace App\Http\Traits;

use App\Http\Helpers\ModuleHelpers;
use App\Models\RelatedEntry;
use Illuminate\Support\Facades\DB;

trait Table
{
    //
    protected function related_list_function($model,$request){
        $id = $request->id;
        $module = $request->module;
        $related_module = $request->related_module;
        $module_details = ModuleHelpers::module_details_name($module)->id;
        $related_module_details = ModuleHelpers::module_details_name($related_module)->id;
        $related_entries_id = RelatedEntry::where('module_id',$id)
            ->where("module",$module_details)
            ->where("related_module",$related_module_details)
            ->pluck('related_id');
        if($request->option == 1){
            $model = $model->whereNotIn('id',$related_entries_id);
        }   
        else{
            $model = $model->whereIn('id',$related_entries_id);
        }
        if(isset($request->search)){
            if($request->search['value'] != ""){
                foreach($request->search['search_field'] as $search_field){
                    $parse = explode(".",$search_field);
                    $search = $request->search['value'];
                    if(count($parse) == 2){
                        $col = $parse[0];
                        $relation_model = DB::table($parse[0])->select('id')->where('label','like',"$search%");
                        $model = $model->whereIn("$col",$relation_model);
                    }   
                    else{
                        $model = $model->where($search_field,"like","$search%");
                    }
                }
            }
        }
        $model = $model->where('deleted',0);
        $model = $model->orderByDesc('updated_at');
        if(isset($request->per_page)){
            $model = $model->paginate($request->per_page);
        }
        else{
            $model = $model->paginate(15);
        }
        
        return $model;
    }
}
