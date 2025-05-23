<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponse;
use App\Models\Incident;
use App\Models\IncidentPriority;
use App\Models\IncidentStatus;
use App\Models\InsightReport;
use App\Models\RelatedEntry;
use App\Models\Resource;
use App\Models\Responder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsightReportController extends Controller
{
    //
    use HttpResponse;
    public function get(string $id){
        $model = InsightReport::find($id);
        $output = [
            "name"      => $model->name,
            "type"      => $model->type,
            "start_date" => Carbon::parse($model->start_date)->format("F d, Y"),
            "end_date" => Carbon::parse($model->end_date)->format("F d, Y"),
            "start_time" => Carbon::parse($model->start_date)->format("g:i A"),
            "end_time" => Carbon::parse($model->end_date)->format("g:i A"),
        ];
        return $this->response($output);
    }
    public function report(string $id,string $option){
        $model = InsightReport::find($id);
        $response = [];
        switch ($option) {
            case 'incident-overview':
                $response = $this->incident_overview($model->start_date,$model->end_date);
                break;
            case 'incident-breakdown-priorities':
                $response = $this->incident_breakdown_priorities($model->start_date,$model->end_date);
                break;
            case 'incident-breakdown-types':
                $response = $this->incident_breakdown_types($model->start_date,$model->end_date);
                break;
            case 'resource-breakdown-types':
                $response = $this->resource_breakdown_types($model->start_date,$model->end_date);
                break;
            case 'responder-breakdown-incident':
                $response = $this->responder_breakdown_incident($model->start_date,$model->end_date);
                break;
            case 'responder-breakdown-response':
                $response = $this->responder_breakdown_response($model->start_date,$model->end_date);
                break;
            default:
                # code...
                break;
        }
        return $this->response($response);
    }
    public function incident_overview($start,$end){
        $incident_model = Incident::whereBetween('created_at',[$start,$end])->where('deleted',0)->get();
        $incident_resolve = $incident_model->filter(function($item){
            return $item->incident_statuses == 3;
        });
        $incident_ongoing = $incident_model->filter(function($item){
            return $item->incident_statuses == 1 || $item->incident_statuses == 2;
        });
        $incident_closed = $incident_model->filter(function($item){
            return $item->incident_statuses == 4;
        });
        return [
            ["name" => "Total Incidents Reported","count" => $incident_model->count()],
            ["name" => "Total Ongoing Incidents","count" => $incident_ongoing->count()],
            ["name" => "Total Resolved Incidents","count" => $incident_resolve->count()],
            ["name" => "Total Closed Incidents","count" => $incident_closed->count()],
        ];
    }
    public function incident_breakdown_priorities($start,$end){
        $output = [];
        
        $low = Incident::select(
            DB::raw('count(incidents.incident_priorities) as count'),
            DB::raw('"Low" as label')
        )->where('incident_priorities',1)
        ->whereBetween('created_at',[$start,$end])
        ->where('deleted',0);
        $medium = Incident::select(
            DB::raw('count(incidents.incident_priorities) as count'),
            DB::raw('"Medium" as label')
        )->where('incident_priorities',2)
        ->whereBetween('created_at',[$start,$end])
        ->where('deleted',0);
        $high = Incident::select(
            DB::raw('count(incidents.incident_priorities) as count'),
            DB::raw('"High" as label')
        )->where('incident_priorities',3)
        ->whereBetween('created_at',[$start,$end])
        ->where('deleted',0);
        $critical = Incident::select(
            DB::raw('count(incidents.incident_priorities) as count'),
            DB::raw('"Critical" as label')
        )->where('incident_priorities',4)
        ->whereBetween('created_at',[$start,$end])
        ->where('deleted',0);
        $incident_model = $low->union($medium)->union($high)->union($critical)->get();
        $incident_model_total = Incident::whereBetween('created_at',[$start,$end])->where('deleted',0);
        foreach($incident_model as $item){
            array_push(
                $output,
                array(
                    "name"      => $item->label,
                    "count"     => $item->count,
                    "total"     => $item->count == 0 ? "0%" : number_format(((int)$item->count/$incident_model_total->count()) * 100,2)."%"
                )
            ); 
        }
        return $output;
    }
    public function incident_breakdown_types($start,$end){
        $output = [];
        $incident_model = Incident::with('incident_types_')->select('incident_types',DB::raw('count(incident_types) as count'))
        ->whereBetween('created_at',[$start,$end])
        ->where('deleted',0)
        ->groupBy('incident_types')
        ->get();
        $incident_model_total = Incident::whereBetween('created_at',[$start,$end]);
        foreach($incident_model as $item){
            array_push(
                $output,
                array(
                    "name"      => $item->incident_types_->label ?? "",
                    "count"     => $item->count,
                    "total"     => number_format(((int)$item->count/$incident_model_total->count()) * 100,2)."%"
                )
            ); 
        }
        return $output;
    }
    public function resource_breakdown_types($start,$end){
        $output = [];
        $incident_model = Incident::whereBetween('created_at',[$start,$end])->where('deleted',0)->pluck("id");
        $resource_model = Resource::with('resources_types_','resources_categories_')
        ->join('related_entries','resources.id','=','related_entries.related_id')
        ->where('related_entries.module',1)
        ->where('related_entries.related_module',2)
        ->whereIn('related_entries.module_id',$incident_model)
        ->get();
        foreach($resource_model as $item){
            array_push(
                $output,
                array(
                    "resource_name"         => $item->resources_name,
                    "resource_type"         => $item->resources_types_->label ?? "",
                    "resource_category"     => $item->resources_categories_->label ?? ""
                )
            ); 
        }
        return $output;
    }
    public function responder_breakdown_incident($start,$end){
        $output = [];
        $model = Responder::with('responder_types_')
        ->join('related_entries','related_entries.related_id','=','responders.id')
        ->join('incidents','related_entries.module_id','=','incidents.id')
        ->select("responders.*","incidents.incident_no")
        ->where('related_entries.module',1)
        ->where('related_entries.related_module',6)
        ->whereBetween('incidents.created_at',[$start,$end])
        ->where('incidents.deleted',0)
        ->get();
        foreach($model as $item){
            array_push(
                $output,
                array(
                    "incident_no"         => $item->incident_no,
                    "responder_unit"         => $item->responder_types_->label ?? "",
                    "responder_name"     => $item->firstname." ".$item->lastname
                )
            ); 
        }
        return $output;
    }
    public function responder_breakdown_response($start,$end){
        $output = [];
        $model = Incident::select('incidents.incident_no',
        'incident_types.label as incident_type',
        'responder_types.label as responder_unit',
        'incident_statuses.label as status',
        'incidents.created_at'
        )
        ->addSelect(DB::raw("(SELECT created_at from responder_logs where incident_id = incidents.id and incident_statuses = 2) as response_time "))
        ->addSelect(DB::raw("(SELECT created_at from responder_logs where incident_id = incidents.id and incident_statuses = 3) as resolution_time "))
        ->join('related_entries','incidents.id','=','related_entries.module_id')
        ->join('responders','responders.id','=','related_entries.related_id')
        ->join('incident_types','incident_types.id','=','incidents.incident_types')
        ->join('responder_types','responder_types.id','=','responders.responder_types')
        ->join('incident_statuses','incident_statuses.id','=','incidents.incident_statuses')
        ->where('related_entries.module',1)
        ->where('related_entries.related_module',6)
        ->where('incidents.deleted',0)
        ->whereBetween('incidents.created_at',[$start,$end])
        ->get();
        foreach($model as $item){
            array_push(
                $output,
                array(
                    "incident_no"           => $item->incident_no,
                    "incident_type"         => $item->incident_type,
                    "responder_unit"        => $item->responder_unit,
                    "response_time"         => $this->time_diff($item->created_at,$item->response_time),
                    "resolution_time"       => $this->time_diff($item->created_at,$item->resolution_time),
                    "status"                => $item->status,
                )
            ); 
        }
        return $output;
    }
    public function time_diff($date1,$date2){
        $time_diff = "";
        if($date2 != null){
            $date1 = Carbon::parse($date1);
            $date2 = Carbon::parse($date2);
            $time_diff = $date1->diff($date2)->format("%H:%i:%s");
        }
        return $time_diff;
    }
}
