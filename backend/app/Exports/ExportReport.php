<?php

namespace App\Exports;

use App\Http\Traits\Encryption;
use App\Http\Traits\ReportTraits;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportReport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use ReportTraits,Encryption;
    private $report_model;
    public function __construct($report_model)
    {
        $this->report_model = $report_model;
    }
    public function collection()
    {
        //
        $report_model = $this->report_model;
        $report_column = $report_model->report_columns;
        $report_condition = $report_model->report_conditions;
        list($columns) = $this->report_column($report_column);
        if(!empty($report_model->related_module)){
            $model = $this->report_relation($report_model->module,$report_model->related_module,$report_column);
        }
        else{
            $model = $this->report($report_model->module,$report_column);
        }
        $model = $model->select($columns);
        $model = $this->report_condition($model,$report_condition);
        $results = $model->get();
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
        return $results;
    }
    public function headings():array
    {
        $header = [];
        $report_columns = $this->report_model->report_columns;
        foreach($report_columns as $column){
            $header[] = $column->label;
        }
        return $header;
    }
}
