<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\ResourcesCategory;
use App\Models\ResourcesType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datas = [
            [
                "resources_name" => "Firefighter Team Alpha",
                "resources_types" => "Personnel",
                "resources_categories" => "Firefighter"
            ],
            [
                "resources_name" => "Paramedic Response 1",
                "resources_types" => "Personnel",
                "resources_categories" => "Paramedic"
            ],
            [
                "resources_name" => "Police Officer Unit 3",
                "resources_types" => "Personnel",
                "resources_categories" => "Police Officer"
            ],
            [
                "resources_name" => "Volunteer Support Team",
                "resources_types" => "Personnel",
                "resources_categories" => "Volunteer"
            ],
            [
                "resources_name" => "Technical Repair Crew",
                "resources_types" => "Personnel",
                "resources_categories" => "Technician"
            ],
            [
                "resources_name" => "Paramedic Response 1",
                "resources_types" => "Personnel",
                "resources_categories" => "Paramedic"
            ],
            [
                "resources_name" => "Ambulance Unit 2",
                "resources_types" => "Vehicles",
                "resources_categories" => "Ambulance"
            ],
            [
                "resources_name" => "Fire Truck Bravo",
                "resources_types" => "Vehicles",
                "resources_categories" => "Fire Truck"
            ],
            [
                "resources_name" => "Police Patrol Car 7",
                "resources_types" => "Vehicles",
                "resources_categories" => "Police Car"
            ],
            [
                "resources_name" => "Rescue Boat Delta",
                "resources_types" => "Vehicles",
                "resources_categories" => "Rescue Boat"
            ],
            [
                "resources_name" => "Emergency Helicopter 1",
                "resources_types" => "Vehicles",
                "resources_categories" => "Helicopter"
            ],
        ];

        foreach($datas as $key => $data){
            $model = new Resource();
            foreach($data as $field => $value){
                if($field == "resources_types"){
                    $d = ResourcesType::where("label",$value)->first();
                    $model->$field = $d->id ?? 1;
                }
                else if($field == "resources_categories"){
                    $d = ResourcesCategory::where("label",$value)->first();
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
