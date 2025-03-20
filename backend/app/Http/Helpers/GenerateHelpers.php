<?php
namespace App\Http\Helpers;

use App\Models\Field;
use App\Models\Incident;
use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class GenerateHelpers{
    public static function get($module){
        // $module_model = Module::where('name',$module)->whereNotNull('prefix')->first();
        $count = DB::table($module)->count();
        $date = Carbon::now()->format("Ym");
        $output = [];
        $output[] =  $date ?? "";
        $output[] = Str::padLeft((int) $count+1,3,0); 
        return $output;
    }
}