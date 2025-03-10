<?php
namespace App\Http\Helpers;

use App\Models\Field;
use App\Models\Incident;
use App\Models\Module;
use Illuminate\Support\Facades\DB;

class GenerateHelpers{
    public static function get($module){
        $module_model = Module::where('name',$module)->whereNotNull('prefix')->first();
        $count = DB::table($module)->count();
        $output = [];
        $output[] = $module_model->prefix ?? "";
        $output[] = (int) $count+1; 
        return $output;
    }
}