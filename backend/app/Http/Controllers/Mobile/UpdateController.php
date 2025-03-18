<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Traits\MobileTokenTrait;
use App\Http\Traits\RelatedEntries;
use App\Models\Incident;
use App\Models\IncidentStatus;
use App\Models\Media;
use App\Models\ResponderLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    //
    use MobileTokenTrait,RelatedEntries;
    public function update(Request $request){
        $data               = json_decode($request->getContent(),true);
        $token              = $data['data']['token']; 
        $status             = $data['data']['status'];
        $incidentid         = $data['data']['incidentid'];
        $token_data         = $this->token($token);
        if($token_data == null){
            return response()->json([
                "status"    => "Error",
                "result"    => "Invalid token!"
            ]);
        }
        $status_id          = IncidentStatus::where('label',$status)->first();
        $incident_model     = Incident::find($incidentid);
        if(isset($data['data']['remarks'])){
            $remarks = $data['data']['remarks'];
            $name = $token_data->firstname." ".$token_data->lastname;
            $incident_model->remarks = $this->update_remarks($name,$status,$incident_model->remarks,$remarks);
        }
        $incident_model->incident_statuses = $status_id->id;
        $incident_model->save();

        $find_responder = ResponderLog::where('incident_id',$incidentid)->where('incident_statuses',$status_id->id)->first();
        if(!$find_responder){
            $responder_logs = new ResponderLog();
            $responder_logs->incident_id = $incidentid;
            $responder_logs->responder_id = $token_data->id;
            $responder_logs->incident_statuses = $status_id->id;
            $responder_logs->save();
        }

        return response()->json([
            "status"    => "Success",
            "result"    => "Successfully change status to " . $status
        ]);
    }
    

    public function update_remarks($name,$status,$current_remarks,$remarks){
        $format  = 'Responder Name: ' . $name . PHP_EOL;
		$format .= 'Date & Time: ' . Carbon::now() . PHP_EOL;
		$format .= 'Status: ' . $status . PHP_EOL;
		$format .= 'Remarks: ' . $remarks . PHP_EOL;
		$format .= '------------------------------------------' . PHP_EOL;
		$format .= $current_remarks;
        return $format;
    }

    public function upload(Request $request){

        if($request->hasFile('attachment')){
            $file = $request->file('attachment');
            $id = $request->incidentid;
            if($path = $file->store("mobile/attachment",["disk"=>'public'])){
                $model = new Media();
                $filename = $file->getClientOriginalName();
                $filename = explode(".",$filename)[0];
                $extension = $file->getClientOriginalExtension();
                $filetype = $file->getClientMimeType();
                $model->filename = $filename;
                $model->extension = $extension;
                $model->filetype = $filetype;
                $model->path = asset('storage')."/".$path;
                $model->filetitle = "Bayan911 App";
                $model->note = $request->note;
                $model->assigned_to = $request->assigned_to;
                $model->source = 'mobile';
                $model->save();
                $this->save_related_entries($id,'incidents',$model->id,'media');
                // ActivityLogsHelpers::log($id,'incidents',$status = 4,related_module:'media',related_item_id:$model->id);

                return response()->json([
                    "status"    => "Success",
                    "result"    => "The file was successfully uploaded.",
                ]);
            }
            else{
                return response()->json([
                    "status"    => "Error",
                    "result"    => "The file was not uploaded.",
                ]);
            }
        }
    }
}
