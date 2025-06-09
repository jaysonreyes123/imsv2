<?php

namespace Database\Seeders;

use App\Models\IncidentType;
use App\Models\Preplan;
use App\Models\ResponseLevel;
use App\Models\RiskLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $prePlans = [
            [
                'preplan_name'   => 'MetroSafe Plan',
                'incident_types' => 'Accident',
                'location'       => 'Quezon City',
                'risk_levels'    => 'High',
                'response_levels'=> 'Local'
            ],
            [
                'preplan_name'   => 'FireGuard Initiative',
                'incident_types' => 'Fire',
                'location'       => 'Makati City',
                'risk_levels'    => 'Critical',
                'response_levels'=> 'Region'
            ],
            [
                'preplan_name'   => 'FloodWatch Protocol',
                'incident_types' => 'Flood',
                'location'       => 'Marikina City',
                'risk_levels'    => 'High',
                'response_levels'=> 'Local'
            ],
            [
                'preplan_name'   => 'MedAssist Protocol',
                'incident_types' => 'Medical',
                'location'       => 'Taguig City',
                'risk_levels'    => 'Moderate',
                'response_levels'=> 'National'
            ],
            [
                'preplan_name'   => 'UrbanRescue Plan',
                'incident_types' => 'Accident',
                'location'       => 'Pasig City',
                'risk_levels'    => 'Low',
                'response_levels'=> 'Local'
            ],
            [
                'preplan_name'   => 'BlazeShield Plan',
                'incident_types' => 'Fire',
                'location'       => 'Mandaluyong City',
                'risk_levels'    => 'Critical',
                'response_levels'=> 'Region'
            ],
            [
                'preplan_name'   => 'AquaGuard Protocol',
                'incident_types' => 'Flood',
                'location'       => 'Caloocan City',
                'risk_levels'    => 'Moderate',
                'response_levels'=> 'Local'
            ],
            [
                'preplan_name'   => 'HealthFirst Plan',
                'incident_types' => 'Medical',
                'location'       => 'Manila City',
                'risk_levels'    => 'High',
                'response_levels'=> 'National'
            ],
            [
                'preplan_name'   => 'QuickRescue Plan',
                'incident_types' => 'Accident',
                'location'       => 'Pasay City',
                'risk_levels'    => 'Low',
                'response_levels'=> 'Local'
            ],
            [
                'preplan_name'   => 'FireSafe Initiative',
                'incident_types' => 'Fire',
                'location'       => 'Quezon City',
                'risk_levels'    => 'High',
                'response_levels'=> 'Region'
            ]
        ];

        foreach($prePlans as $keys => $datas){
            $model = new Preplan();
            foreach($datas as $key => $value){
                if($key == "incident_types"){
                    $d = IncidentType::where("label",$value)->first();
                    $model->$key = $d->id ?? 1;
                }
                else if($key == "risk_levels"){
                    $d = RiskLevel::where("label",$value)->first();
                    $model->$key = $d->id ?? 1;
                }
                else if($key == "response_levels"){
                    $d = ResponseLevel::where("label",$value)->first();
                    $model->$key = $d->id ?? 1;
                }
                else{
                    $model->$key = $value;
                }

            }
            $model->save();
        }

    }
}
