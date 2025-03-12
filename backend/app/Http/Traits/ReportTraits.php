<?php

namespace App\Http\Traits;

use App\Http\Helpers\ModuleHelpers;
use App\Models\RelatedEntry;
use Illuminate\Support\Facades\DB;

trait ReportTraits
{
    //
    protected $relation = ["incident_types","incident_statuses","incident_priorities","resources_statuses","resources_types","resources_categories","risk_levels","response_levels","responder_types","tasks_statuses"];
    protected function report_column_chart($report_columns){
        $columns = [];
        $column_for_table = [];
        $groupby = [];
        foreach($report_columns as $report_column){
            $column_for_table[] = array("label" => $report_column->label,"field" => $report_column->column);
            $columns[] = DB::raw("count($report_column->column) as count");
            if(in_array($report_column->column,$this->relation)){
                $columns[] = $report_column->column.".label as label";
                $groupby[] = $report_column->column.".label";
            }
            else{
                $columns[] = $report_column->module.".".$report_column->column." as label";
                $groupby[] = $report_column->column;
            }
        }
        return [$columns,$column_for_table,$groupby];
    }
    protected function report_column($report_columns){
        $columns = [];
        $column_for_table = [];
        foreach($report_columns as $report_column){
            $column_for_table[] = array("label" => $report_column->label,"field" => $report_column->column);
            if(in_array($report_column->column,$this->relation)){
                $columns[] = $report_column->column.".label as ".$report_column->column;
            }
            else{
                $columns[] = $report_column->module.".".$report_column->column;
            }
        }
        return [$columns,$column_for_table];
    }
    protected function report_condition($model,$report_conditions){
        foreach($report_conditions as $report_condition){
            $column = $report_condition->module.".".$report_condition->column;
            $operator = $report_condition->operator;
            $value = $report_condition->value;
            // model condition
            $model = $model->where($column,$operator,$value);
        }
        return $model;
    }
    protected function report_relation($primary_table,$related_tables,$report_columns){
        $model = RelatedEntry::leftJoin($primary_table,'related_entries.module_id','=',"$primary_table.id");
        foreach($related_tables as $secondary_table){
            // $model = $model->leftJoin("$secondary_table",'related_entries.related_id','=',"$secondary_table.id");
            $related_module_id = ModuleHelpers::module_details_name($secondary_table)->id;
            $model = $model->leftJoin($secondary_table,function($join) use ($secondary_table,$related_module_id){
                $join->on('related_entries.related_id','=',"$secondary_table.id");
                $join->on("related_entries.related_module","=",DB::raw('CAST('.$related_module_id.' as int)'));
            });
        }
        foreach($report_columns as $report_column){
            if(in_array($report_column->column,$this->relation)){
                $column = $report_column->column;
                $model->leftJoin($column,"$column.id",'=',$primary_table.".".$column);
            }
        }
        return $model->distinct();
    }
    protected function report($primary_table,$report_columns){
        $model = DB::table($primary_table);
        foreach($report_columns as $report_column){
            if(in_array($report_column->column,$this->relation)){
                $column = $report_column->column;
                $model->leftJoin($column,"$column.id",'=',$primary_table.".".$column);
            }
        }
        return $model;
    }
}
