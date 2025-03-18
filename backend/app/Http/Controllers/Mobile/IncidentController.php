<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Traits\MobileTokenTrait;
use App\Models\Incident;
use App\Models\IncidentStatus;
use App\Models\ResponderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncidentController extends Controller
{
    //
    use MobileTokenTrait;
    public function list(Request $request){
        $data               = json_decode($request->getContent(),true);
        $token              = $data['data']['token'];
        $department         = $data['data']['department'];
        $status             = $data['data']['status'];
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }

        $department_id  = ResponderType::where('label',$department)->first();
        $incident_model = Incident::with('incident_types_','incident_statuses_')
        ->whereHas("incident_statuses_",function($relation) use ($status){
            return $relation->where('label',$status);
        })
        ->whereJsonContains('responder_types',$department_id->id)
        ->get();
        $incident_list = [];
        if(!$incident_model->isEmpty()){
            foreach($incident_model as $incident){
                $incident_list[] = 
                [
                    "id"                => $incident->id,
                    "incidentno"        => $incident->incident_no,
                    "incidenttype"      => $incident->incident_types_->label ?? "",
                    "description"       => $incident->description,
                    "status"            => $incident->incident_statuses_->label ?? "",
                    "location"          => $incident->location. ' Nearest Landmark '.$incident->nearest_landmark,
                    "coordinates"       => $incident->coordinates, //Coordinates
                    "createddatetime"   => $incident->created_at,
                ];
            }
        }
        return response()->json([
            "status" => "Success",
            "result" => $incident_list
        ]);
    }

    public function dropdown(Request $request){
        if($request->name == 'status'){
            return $this->status($request->name);
        }
    }
    public function status($name){
        $model = IncidentStatus::get();
        $result = [];
        foreach($model as $item){
            $result[] = 
            [
                "id" => $item->id,
                "name" => $item->label
            ];
        }
        return response()->json([
            "status" => "Success",
            "result" => $result
        ]);
    }
}
