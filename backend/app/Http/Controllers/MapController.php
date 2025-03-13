<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponse;
use App\Models\Incident;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    //
    use HttpResponse;
    public function index($model,$start=null,$end=null){
        $map_data = null;
        if($model == 'incidents'){
            $map_data = $this->incident();
        }
        else if($model == 'resources'){
            $map_data = $this->resources();
        }
        else if($model == 'heat_map'){
            $start = $start == null ? Carbon::now()->format('Y-m-d') : $start;
            $end = $end == null ? Carbon::now()->format('Y-m-d') : $end;
            $map_data = $this->heat_map($start,$end);
        }
        return $map_data;
    }
    public function incident(){
        $model =   Incident::where('deleted',0)->whereNotNull('coordinates')->whereIn('incident_statuses',[1,2])->get();
        $result = [];
        $result["type"] = "FeatureCollection";
        $result["features"] = array();
        foreach($model as $item){   
            $coordinates = explode(",",$item->coordinates);
            array_push(
                $result["features"],
                array(
                    "type" => "Feature",
                    "properties" => array(
                        "id"                => $item->id,
                        "incident_no"       => $this->dropdown("incident_types",$item->incident_types),  
                        "status"            => $this->dropdown('incident_statuses',$item->incident_statuses),
                        "location"          => $item->location
                    ),
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => array(
                            (float) $coordinates[0], (float) $coordinates[1]
                        )
                    ),
                )
            );
        }
        return $this->response($result);
    }

    public function resources(){
        $model = Resource::where('deleted',0)->whereNotNull('coordinates')->get();
        $result = [];
        $result["type"] = "FeatureCollection";
        $result["features"] = array();
        foreach($model as $item){   
            $coordinates = explode(",",$item->coordinates);
            array_push(
                $result["features"],
                array(
                    "type" => "Feature",
                    "properties" => array(
                        "id"                => $item->id,
                        "name"              => $item->resources_name,
                        "type"            =>   $item->resources_types->name ?? "",
                        "status"            => $item->resources_statuses->name ?? "",
                    ),
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => array(
                            (float) $coordinates[0], (float) $coordinates[1]
                        )
                    ),
                )
            );
        }
        return $this->response($result);
    }
    public function dropdown($field,$value){
        $dropdown_model = DB::table($field)->where('id',$value)->first();
        return $dropdown_model->label ?? "";
    }
    public function heat_map($start,$end){
        $model = Incident::where('deleted',0)
        ->whereDate('created_at','>=',$start)
        ->whereDate('created_at','<=',$end)
        ->where('incident_statuses','Resolved')
        ->whereNotNull('coordinates')->get();
        $result = [];
        $result["type"] = "FeatureCollection";
        $result["features"] = array();
        foreach($model as $item){   
            $coordinates = explode(",",$item->coordinates);
            array_push(
                $result["features"],
                array(
                    "type" => "Feature",
                    "properties" => array(
                        "dbh"               => 10,
                        "id"                => $item->id,
                        "incident_no"       => $item->incident_no,  
                        "status"            => $item->incident_statuses->name ?? "",
                        "location"          => $item->location
                    ),
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => array(
                            (float) $coordinates[0], (float) $coordinates[1]
                        )
                    ),
                )
            );
        }
        return $this->response($result);
    }
}
