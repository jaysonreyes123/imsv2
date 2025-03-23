<?php

namespace App\Http\Controllers;

use App\Exports\ExportReport;
use App\Http\Helpers\ModuleHelpers;
use App\Http\Resources\ReportResource;
use App\Http\Traits\Encryption;
use App\Http\Traits\HttpResponse;
use App\Http\Traits\ReportTraits;
use App\Models\Incident;
use App\Models\RelatedEntry;
use App\Models\Report;
use App\Models\ReportChart;
use App\Models\ReportColumn;
use App\Models\ReportCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ReportTraits,HttpResponse,Encryption;
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return $this->response($this->save($request));
    }
    public function save_filter($filters,$report_id){
        foreach($filters as $filter){
            if($filter['field'] != ""){
                $report_condition_model = new ReportCondition();
                $report_condition_model->report_id = $report_id;
                $report_condition_model->column = $filter['field'];
                $report_condition_model->operator = $filter['operator'];
                $report_condition_model->value = $filter['value'];
                $report_condition_model->type = $filter['type'];
                $report_condition_model->module = $filter['module'];
                $report_condition_model->save();
            }   
        }
    }
    public function save_column($columns,$report_id){
        foreach($columns as $column){
            $column_model = new ReportColumn();
            $column_model->report_id = $report_id;
            $column_model->module = $column['module'];
            $column_model->column = $column['name'];
            $column_model->label = $column['label'];
            $column_model->save();
        }
    }
    public function save_chart($report_chart_model,$request,$report_id){
            $report_chart_model->report_id      = $report_id;
            $report_chart_model->chart          = $request->chart['type'];
            $report_chart_model->dataset        = $request->chart['dataset'];
            $report_chart_model->groupby        = $request->chart['group_by'][0]['name'];
            $report_chart_model->save();
    }
    public function save($request,$id = ""){
        if($id == ""){
            $model = new Report();
            $report_chart_model = new ReportChart();
        }
        else{
            $model = Report::find($id);
            $report_chart_model = ReportChart::where('report_id',$id)->first();
            ReportColumn::where("report_id",$id)->delete();
            ReportCondition::where("report_id",$id)->delete();
        }
        $model->report_name = $request->report_name;
        $model->module = $request->module;
        $model->related_module = $request->related_module;
        $model->type = $request->option;
        if($model->save()){
            $selected_column = $request->selected_columns;
            if($request->option == 'chart'){
                $selected_column = $request->chart['group_by'];
                $this->save_chart($report_chart_model,$request,$model->id);
                
            }
            $this->save_column($selected_column,$model->id);
            $this->save_filter($request->filter,$model->id);
        }
        return $model->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        //
        $report_model = Report::find($id);
        $report_column = $report_model->report_columns;
        $report_condition = $report_model->report_conditions;
        $report_chart = $report_model->report_charts;
        if($report_model->type == 'list'){
            list($columns,$column_for_table) = $this->report_column($report_column);
        }
        else{
            list($columns,$column_for_table,$groupby) = $this->report_column_chart($report_column);
        }
        // if has relation module 
        if(!empty($report_model->related_module)){
            $model = $this->report_relation($report_model->module,$report_model->related_module,$report_column);
        }
        else{
            $model = $this->report($report_model->module,$report_column);
        }
        $model = $model->select($columns);
        $model = $this->report_condition($model,$report_condition);
        if($report_model->type == 'chart'){
            $model = $model->groupBy($groupby);
            $results = $model->get();
        }
        else{
            if($request->limit != 0){
                $model->limit($request->limit);
           }
           $results = $model->get();
        }
        $columns_ = [];
        foreach($report_column as $report_col){
            $columns_[] = $report_col->column;
        }
        $encrypted_fields = array_intersect($columns_,$this->encrypted_field);
        $results = $results->filter(function($item) use ($encrypted_fields,$report_model){
            foreach($encrypted_fields as $encrypted_field){
                $report_field = $report_model->type == 'chart' ? 'label' : $encrypted_field;
                $data = $this->decrypt_single($encrypted_field,$item->$report_field);
                $item->$report_field = $data;
            }
            return $item;
        });
        return $this->response([
            "details" => $report_model,
            "column" => $column_for_table,
            "data" => $results
        ]);
        
    }
    public function edit(string $id){
        $report_model = Report::with('report_columns','report_conditions','report_charts')->find($id);
        return $this->response($report_model);
    }
    public function generate($type,$id){
        $report_model = Report::find($id);
        $report_name = $report_model->report_name.".".$type;
        $format = $type == 'xlsx' ? \Maatwebsite\Excel\Excel::XLSX : \Maatwebsite\Excel\Excel::CSV;
        return Excel::download(new ExportReport($report_model),$report_name,$format);
    }

    /**
     * Update the specified resource in storage=     */
    public function update(Request $request, string $id)
    {
        //
        return $this->response($this->save($request,$id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
