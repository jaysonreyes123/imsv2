<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardActivityLogsResource;
use App\Http\Traits\HttpResponse;
use App\Models\ActivityMain;
use App\Models\Incident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
     use HttpResponse;
     public function get_widget($module,$field = null,$operator = null,$value = null){
        $model = null;
        if($module == 'call_takers'){
            $model = User::where('status',$value)->where('user_roles',3)->get();
        }
        else{
            $model = DB::table($module)->where("deleted",0);
            if($field != null && $operator != null && $value != null){
                $parse_value = explode(":",$value);
                $model->select($field);
                $where_data = [];
                foreach($parse_value as $v){
                    // $model->where($field,$operator,$v);
                    $where_data[] = $v;
                }
                $model = $operator == '<>' ? $model->whereNotIn($field,$where_data) : $model->whereIn($field,$where_data);
            }
        }
        return $this->response($model->count());
    }
    public function incident_status(){
        $model = Incident::with('incident_statuses_')
        ->where("deleted",0)
        ->whereNotNull('incident_statuses')
        ->select("incident_statuses",DB::raw("count(id) as count"))
        ->groupBy('incident_statuses')
        ->orderBy('incident_statuses')
        ->get();
        $output = [];
        if($model->count() == 0){
            $output['label'][] = "No Data";
            $output['count'][] = 0;
        }
        else{
            foreach($model as $row){
                $output['label'][] = $row->incident_statuses_->label ?? "";
                $output['count'][] = $row->count;
            }
        }
        return $this->response($output);
    }
    public function incident_trend_month(){
        $current_date = Carbon::now()->format("Y-m-d");
        //6month
        $date = Carbon::now()->subMonth(6)->format("Y-m-d");
        $model = Incident::whereDate('created_at',">=",$date)
        ->whereDate("created_at","<=",$current_date)
        ->where("deleted",0)
        ->select(DB::raw("count(id) as count"),DB::raw("DATE_FORMAT(created_at,'%M %Y') as month"))
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        $output = [];
        if($model->count() == 0){
            $output['label'][] = "No Data";
            $output['count'][] = 0;
        }
        else{
            foreach($model as $row){
                $output['label'][] = $row->month;
                $output['count'][] = $row->count;
            }
        }
        return $this->response($output);
    }
    public function get_systemlogs(){
        $model = ActivityMain::orderByDesc('created_at')->paginate(20);
        return DashboardActivityLogsResource::collection($model);
    }
}
