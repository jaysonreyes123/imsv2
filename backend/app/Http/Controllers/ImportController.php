<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponse;
use App\Imports\ImportModuleData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    //
    use HttpResponse;
    public function import_data(Request $request){
        $files = $request->file('file');
        $data = Excel::toCollection(new ImportModuleData($request),$files);
        foreach ($data as $value) {
            return $this->response($value[0]);
        }
    }
    public function save(Request $request){
        $files  = $request->file('file');
        $fields = $request->fields;
        $import_user_table = "import_user_".Auth::id();
        $this->create_table($fields,$import_user_table); 
        Excel::import(new ImportModuleData($request),$files);
        return $this->response($this->import_result($import_user_table));
    }

    protected function import_result($table){
        return DB::table($table)->get();
     }
    protected function create_table($fields,$table){
        Schema::dropIfExists($table);
        Schema::create($table,function(Blueprint $table) use ($fields) {
            foreach($fields as $key => $field){
                $table->string($field['field'])->nullable();
            }
            $table->string('message')->nullable();
            $table->integer('status')->nullable();
        });
    }
}
