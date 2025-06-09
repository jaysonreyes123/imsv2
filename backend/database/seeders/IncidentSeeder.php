<?php

namespace Database\Seeders;

use App\Http\Helpers\GenerateHelpers;
use App\Models\Incident;
use App\Models\IncidentPriority;
use App\Models\IncidentStatus;
use App\Models\IncidentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datas  =
        [
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0289, 14.6523", "incident_priorities" => "Medium",   "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "In Progress",  "coordinates" => "121.0345, 14.5678", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Resolved",     "coordinates" => "121.0412, 14.5902", "incident_priorities" => "Critical", "street_name" => "Katipunan Ave",     "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Closed",       "coordinates" => "121.0507, 14.6301", "incident_priorities" => "Low",      "street_name" => "Ortigas Ave",       "location" => "Pasig"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0554, 14.6156", "incident_priorities" => "High",     "street_name" => "EDSA",              "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0223, 14.6234", "incident_priorities" => "Medium",   "street_name" => "Taft Ave",          "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "In Progress",  "coordinates" => "121.0298, 14.6750", "incident_priorities" => "Low",      "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Resolved",     "coordinates" => "121.0310, 14.6412", "incident_priorities" => "Critical", "street_name" => "Shaw Blvd",         "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Closed",       "coordinates" => "121.0452, 14.6004", "incident_priorities" => "Medium",   "street_name" => "Boni Ave",          "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0397, 14.6123", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0275, 14.6510", "incident_priorities" => "Low",      "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "In Progress",  "coordinates" => "121.0443, 14.6593", "incident_priorities" => "Critical", "street_name" => "Shaw Blvd",         "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Resolved",     "coordinates" => "121.0357, 14.6701", "incident_priorities" => "Medium",   "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Closed",       "coordinates" => "121.0384, 14.6354", "incident_priorities" => "High",     "street_name" => "Ortigas Ave",       "location" => "Pasig"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0521, 14.6287", "incident_priorities" => "Low",      "street_name" => "EDSA",              "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0218, 14.6448", "incident_priorities" => "Medium",   "street_name" => "Taft Ave",          "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "In Progress",  "coordinates" => "121.0300, 14.6500", "incident_priorities" => "Critical", "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Resolved",     "coordinates" => "121.0347, 14.6255", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Closed",       "coordinates" => "121.0409, 14.6602", "incident_priorities" => "Medium",   "street_name" => "Boni Ave",          "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0455, 14.6703", "incident_priorities" => "Low",      "street_name" => "Ortigas Ave",       "location" => "Pasig"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0296, 14.6389", "incident_priorities" => "Medium",   "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "In Progress",  "coordinates" => "121.0322, 14.6550", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Resolved",     "coordinates" => "121.0379, 14.6207", "incident_priorities" => "Critical", "street_name" => "Katipunan Ave",     "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Closed",       "coordinates" => "121.0414, 14.6313", "incident_priorities" => "Low",      "street_name" => "Shaw Blvd",         "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0488, 14.6466", "incident_priorities" => "Medium",   "street_name" => "EDSA",              "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0251, 14.6568", "incident_priorities" => "High",     "street_name" => "Taft Ave",          "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "In Progress",  "coordinates" => "121.0362, 14.6190", "incident_priorities" => "Low",      "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Resolved",     "coordinates" => "121.0393, 14.6495", "incident_priorities" => "Critical", "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Closed",       "coordinates" => "121.0427, 14.6397", "incident_priorities" => "Medium",   "street_name" => "Boni Ave",          "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0349, 14.6511", "incident_priorities" => "High",     "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0272, 14.6707", "incident_priorities" => "Low",      "street_name" => "Katipunan Ave",     "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "In Progress",  "coordinates" => "121.0495, 14.6661", "incident_priorities" => "Critical", "street_name" => "Ortigas Ave",       "location" => "Pasig"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Resolved",     "coordinates" => "121.0380, 14.6555", "incident_priorities" => "Medium",   "street_name" => "Boni Ave",          "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Closed",       "coordinates" => "121.0333, 14.6317", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0410, 14.6439", "incident_priorities" => "Low",      "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0501, 14.6204", "incident_priorities" => "Medium",   "street_name" => "EDSA",              "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "In Progress",  "coordinates" => "121.0297, 14.6612", "incident_priorities" => "Critical", "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Resolved",     "coordinates" => "121.0358, 14.6461", "incident_priorities" => "High",     "street_name" => "Shaw Blvd",         "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Closed",       "coordinates" => "121.0404, 14.6350", "incident_priorities" => "Medium",   "street_name" => "Katipunan Ave",     "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0278, 14.6583", "incident_priorities" => "Low",      "street_name" => "Taft Ave",          "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0313, 14.6540", "incident_priorities" => "Medium",   "street_name" => "Aurora Blvd",       "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "In Progress",  "coordinates" => "121.0432, 14.6678", "incident_priorities" => "High",     "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Resolved",     "coordinates" => "121.0465, 14.6413", "incident_priorities" => "Critical", "street_name" => "Boni Ave",          "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Closed",       "coordinates" => "121.0325, 14.6294", "incident_priorities" => "Low",      "street_name" => "Ortigas Ave",       "location" => "Pasig"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Open",         "coordinates" => "121.0351, 14.6635", "incident_priorities" => "Medium",   "street_name" => "EDSA",              "location" => "Mandaluyong"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Open",         "coordinates" => "121.0397, 14.6509", "incident_priorities" => "High",     "street_name" => "España Blvd",       "location" => "Manila"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "In Progress",  "coordinates" => "121.0418, 14.6422", "incident_priorities" => "Low",      "street_name" => "Katipunan Ave",     "location" => "Quezon City"],
    ["incident_types" => "Road Traffic Accident",  "incident_statuses" => "Resolved",     "coordinates" => "121.0370, 14.6559", "incident_priorities" => "Critical", "street_name" => "Shaw Blvd",         "location" => "Mandaluyong"],
    ["incident_types" => "Medical Emergency",      "incident_statuses" => "Closed",       "coordinates" => "121.0438, 14.6342", "incident_priorities" => "Medium",   "street_name" => "Commonwealth Ave",  "location" => "Quezon City"],
];
        foreach($datas as $key => $data){
            $model = new Incident();
            list($prefix,$current_count) = GenerateHelpers::get('incidents');
            $model->incident_no = $prefix.$current_count;
            foreach($data as $field => $value){
                if($field == "incident_types"){
                    $d = IncidentType::where("label",$value)->first();
                    $model->$field = $d->id ?? 1;
                }
                else if($field == "incident_statuses"){
                    $d = IncidentStatus::where("label",$value)->first();
                    $model->$field = $d->id ?? 1;
                }
                else if($field == "incident_priorities"){
                    $d = IncidentPriority::where("label",$value)->first();
                    $model->$field = $d->id ?? 1;
                }
                else{
                    $model->$field = $value;
                }
            }
            $model->save();
        }
    }
}
