<?php

namespace App\Imports;

use App\Http\Helpers\ActivityLogsHelpers;
use App\Http\Helpers\FieldValidationHelpers;
use App\Http\Helpers\ModuleHelpers;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportModuleData implements ToModel,WithStartRow
{
    /**
    * @param Collection $collection
    */
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function model(array $row)
    {
        //
        $request = $this->request;
        $duplicate_option = $request->duplicate_option;
        $status = 1;
        $model  = null;
        $message = "";
        $form = [];
        if($duplicate_option == 1){
            $model = ModuleHelpers::new($request->module);
        }
        else{
            $model = ModuleHelpers::import($request->module);
        }
        foreach($request->fields as $key => $fields){
            $type = $fields['type'];
            $field = $fields['field'];
            $value = $row[$fields['position']];

            //validate value
            $validate = FieldValidationHelpers::validate($value,$field,$type);
            if(gettype($validate) == 'array'){
                $status = 0;
                $message = $validate['message'];
                break;
            }
            //check for duplicate handling
            if($request->duplicate_option != 1){
                if(in_array($field,$request->duplicate_field)){
                    $model = $model->where($field,$value);
                }
            }
            $form[$field] = $validate;
        }

        //end field and value mapping
        if($status != 0){
            if($duplicate_option == 1){
                $form_submit = $this->system_generated(1,$form);
                $id = $model->insert($form_submit);
                ActivityLogsHelpers::log($id,$request->module,6,$form);
            }
            //skip
            else if($duplicate_option == 2){
                if($model->count() == 0){
                    $form_submit = $this->system_generated(1,$form);
                    $model->insert($form_submit);
                }
                else{
                    $status = 2;
                }
            }
            //update
            else if($duplicate_option == 3){
                $status = 3;
                $form_submit = $this->system_generated(2,$form);
                $model->update($form_submit);
            }
        }
        $import_user_table = "import_user_".Auth::id();
        $form['status'] = $status;
        $form['message'] = $message;
        DB::table($import_user_table)->insert($form);
    }
    public function startRow():int
    {
        return $this->request->hasheader == 'false' ? 1 : 2;
    }

    public function system_generated($status,$form){
        if($status == 1){
            $form['created_at'] = Carbon::now();
            $form['updated_at'] = Carbon::now();
            $form['created_by'] = Auth::id();
            $form['updated_by'] = Auth::id();
        }
        else{
            $form['updated_at'] = Carbon::now();
            $form['updated_by'] = Auth::id();
        }
        return $form;
    }
}
