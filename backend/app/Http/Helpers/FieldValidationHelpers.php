<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNumeric;

class FieldValidationHelpers{
    public static function validate($value,$field,$type){
        $fields = null;
        if($type == "dropdown"){
            $fields = self::dropdown($field,$value);
        }
        else if($type == "time"){
            $fields = self::time($value);
        }
        else if($type == "number"){
            $fields = self::number($value);
        }
        else if($type == "date"){
            $fields = self::date($value);
        }
        else if($type == "coordinates"){
            $fields = self::coordinates($value);
        }
        else{
            $fields = $value;
        }
        return $fields;
    }
    public static function dropdown($field,$value){
        try {
            $model_value = DB::table($field)->where('label',$value)->first();
        } catch (\Throwable $th) {
            $model_value = null;
        }

        return $model_value->id ?? array('status' => 0 ,'message' => 'Invalid value');
    }
    public static function time($value){
        $parse_time = explode(":",$value);
        $time = null;
        if(count($parse_time) == 2){
            $time = $value;
        }
        return $time == null ? array('status' => 0 ,'message' => 'Invalid Time') : $time; 
    }
    public static function date($value){
        $date = date("Y-m-d",strtotime($value));
        $date_ = date("Y",strtotime($date));
        return $date_ == "1970" ? array('status' => 0 ,'message' => 'Invalid Date') : $date;
    }
    public static function number($value){
        if(!isNumeric($value)){
            return array('status' => 0 ,'message' => 'Not a number');
        }
        return $value;
    }
    public static function coordinates($value){
        $coordinate  = null;
        $parse = explode(",",$value);
        if(count($parse) != 2){
            $coordinate = null;
        }
        else{
            $parse_lat = explode(".",$parse[0]);
            if($parse_lat[0] >= -90 && $parse_lat[0] <= 90){
                $coordinate =  implode(",",array_reverse($parse));
            }
            else{
                $coordinate =  $value;
            }
        }
        return $coordinate == null ? array('status' => 0 ,'message' => 'Invalid Coordinates') : $coordinate;
    }
}