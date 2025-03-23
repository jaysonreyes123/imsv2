<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Traits\MobileTokenTrait;
use App\Http\Traits\RelatedEntries;
use App\Models\Incident;
use App\Models\ResponderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AcceptIncidentController extends Controller
{
    //

    use MobileTokenTrait,RelatedEntries;
    public function accept(Request $request){
        $data               = json_decode($request->getContent(),true);
        $token              = $data['data']['token']; 
        $incidentid         = $data['data']['incidentid'];
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
        $incident_model = Incident::find($incidentid);
        $incident_model->incident_statuses = 2;
        $incident_model->save();
        // responder logs

        $find_responder = ResponderLog::where('incident_id',$incidentid)->where('incident_statuses',2)->first();
        if(!$find_responder){
            $responder_logs = new ResponderLog();
            $responder_logs->incident_id = $incidentid;
            $responder_logs->responder_id = $token_data->id;
            $responder_logs->incident_statuses = 2;
            $responder_logs->save();
            $this->save_related_entries($incidentid,'incidents',$token_data->id,'responders');
        }
        Log::info("Incident Accepted: ",["responder id" => $token_data->id,"incident id" => $incidentid]);
        return response()->json([
            "status"    => "Success",
            "message"   => "Accepted Incident",
            "result"    => "In Progress"
        ]);
    }   
}
