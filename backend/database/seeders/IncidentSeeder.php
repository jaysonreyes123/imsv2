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
        $datas  = [
            [
                "incident_types" => "Road Traffic Accidents",
                "time_of_incident" => "23:54:20",
                "date_of_incident" => "2024-12-09",
                "incident_statuses" => "Resolved",
                "incident_priorities" => "High",
                "location" => "manila",
                "street_name" => "Ayala Avenue",
                "nearest_landmark" => "Manila Bay",
                "coordinates" => "120.982,14.5498"
            ],
            [
                "incident_types" => "Explosion",
                "time_of_incident" => "17:54:20",
                "date_of_incident" => "2024-12-14",
                "incident_statuses" => "Resolved",
                "incident_priorities" => "High",
                "location" => "manila",
                "street_name" => "EDSA",
                "nearest_landmark" => "Shangri-La Plaza",
                "coordinates" => "120.9842,14.5995"
            ],
            [
                "incident_types" => "Medical Emergency",
                "time_of_incident" => "20:54:20",
                "date_of_incident" => "2024-12-26",
                "incident_statuses" => "Closed",
                "incident_priorities" => "Medium",
                "location" => "manila",
                "street_name" => "EDSA",
                "nearest_landmark" => "Araneta Coliseum",
                "coordinates" => "120.9721,14.6507"
            ],
            [
                "incident_types" => "Road Accident",
                "time_of_incident" => "8:54:20",
                "date_of_incident" => "2024-12-10",
                "incident_statuses" => "Resolved",
                "incident_priorities" => "Medium",
                "location" => "manila",
                "street_name" => "Ortigas Avenue",
                "nearest_landmark" => "Shangri-La Plaza",
                "coordinates" => "121.0244,14.5547"
            ],
            [
                "incident_types" => "Other",
                "time_of_incident" => "5:54:20",
                "date_of_incident" => "2024-12-25",
                "incident_statuses" => "Closed",
                "incident_priorities" => "High",
                "location" => "manila",
                "street_name" => "EDSA",
                "nearest_landmark" => "Rizal Park",
                "coordinates" => "121.0355,14.6016"
            ],
            [
                "incident_types" => "Flood",
                "time_of_incident" => "16:54:20",
                "date_of_incident" => "2024-12-07",
                "incident_statuses" => "Resolved",
                "incident_priorities" => "Low",
                "location" => "manila",
                "street_name" => "Ayala Avenue",
                "nearest_landmark" => "Intramuros",
                "coordinates" => "121.0567,14.5176"
            ],
            [
                "incident_types" => "Medical Emergency",
                "time_of_incident" => "1:54:20",
                "date_of_incident" => "2024-12-04",
                "incident_statuses" => "Reported",
                "incident_priorities" => "Medium",
                "location" => "manila",
                "street_name" => "Ayala Avenue",
                "nearest_landmark" => "Rizal Park",
                "coordinates" => "120.9842,14.5995"
            ],
            [
                "incident_types" => "Fire",
                "time_of_incident" => "12:54:20",
                "date_of_incident" => "2024-12-10",
                "incident_statuses" => "Resolved",
                "incident_priorities" => "Low",
                "location" => "manila",
                "street_name" => "Bonifacio High Street",
                "nearest_landmark" => "Intramuros",
                "coordinates" => "121.0437,14.6760"
            ],
            [
                "incident_types" => "Power Outage",
                "time_of_incident" => "23:54:20",
                "date_of_incident" => "2024-12-29",
                "incident_statuses" => "Reported",
                "incident_priorities" => "Medium",
                "location" => "manila",
                "street_name" => "EDSA",
                "nearest_landmark" => "Shangri-La Plaza",
                "coordinates" => "120.9842,14.5995"
            ],
            [
                "incident_types" => "Explosion",
                "time_of_incident" => "9:54:20",
                "date_of_incident" => "2024-12-25",
                "incident_statuses" => "In Progress",
                "incident_priorities" => "High",
                "location" => "manila",
                "street_name" => "Katipunan Avenue",
                "nearest_landmark" => "Araneta Coliseum",
                "coordinates" => "120.994,14.4779"
            ],
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
