<?php

namespace App\Http\Controllers;

use App\Http\Resources\DropdownResource;
use App\Http\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    //
    use HttpResponse;
    // public function get_dropdown($field){
    //     $model = [];
    //     $cache = false;
    //     try {
    //         if(Cache::has($field)){
    //             $model = Cache::get($field);
    //             $cache = true;
    //         }
    //         else{
    //             $model = DB::table($field);
    //             if($field == 'users'){
    //                 $model = DB::table($field)->where('deleted',0);
    //                 $model->orderBy('firstname');
    //             }
    //             else if(
    //                     $field == 'task_statuses' || 
    //                     $field== 'incident_statuses' || 
    //                     $field == 'incident_priorities' || 
    //                     $field == 'risk_levels' || 
    //                     $field == 'response_levels'
    //             ){
    //                 $model->orderBy('sequence');
    //             }
    //             else{
    //                 $model->orderBy('label');
    //             }
    //             $model = $model->get();
    //             if($field != 'users'){
    //                 Cache::add($field,$this->set_dropdown($field,$model));
    //             }
    //         }
           
    //     } catch (\Throwable $th) {
    //         $model = [];
    //     }
    //     if($cache){
    //         return $this->response($model);
    //     }
    //     else{
    //         return $this->response(DropdownResource::collection($model)->additional(['field'=>$field]));
    //     }

    // }
    public function set_dropdown($field,$model){
        $dropdown = [];
        if($model->count() > 0){
            foreach($model as $data){
                if($field == 'users'){
                    $dropdown[] = array("id" => $data->id,"label" => $data->firstname);
                }
                else{
                    $dropdown[] = array("id" => $data->id,"label" => $data->label,"parent_id" => $data->parent_id ?? "");
                }   
            }
        }
        return $dropdown;
    }
    public function get_dropdown($field)
    {
        try {
            return Cache::remember($field, now()->addMinutes(60), function () use ($field) {
                $query = DB::table($field);

                if ($field === 'users') {
                    $query->where('deleted', 0)->orderBy('firstname');
                } elseif (in_array($field, [
                    'task_statuses', 'incident_statuses', 'incident_priorities', 
                    'risk_levels', 'response_levels'
                ])) {
                    $query->orderBy('sequence');
                } else {
                    $query->orderBy('label');
                }

                $model = $query->get();

                return DropdownResource::collection($model)->additional(['field' => $field]);
            });
        } catch (\Throwable $th) {
            return $this->response([]);
        }
    }
}
