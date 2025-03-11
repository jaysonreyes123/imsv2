<?php

namespace Database\Seeders;

use App\Models\ResourcesCategory;
use App\Models\ResourcesType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourcesCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [
                "Firefighter",
                "Paramedic",
                "Police Officer",
                "Volunteer",
                "Technician"
            ],
            [
                "Ambulance",
                "Fire Truck",
                "Police Car",
                "Rescue Boat",
                "Helicopter",
                "Utility Vehicle"
            ],
            [
                "First Aid Kit",
                "Fire Extinguisher",
                "Defibrillator (AED)",
                "Rescue Tools ",
                "Flashlight",
                "Communication Radio",
            ],
            [
                "Hospital",
                "Evacuation Center",
                "Command Center",
                "Shelter",
                "Supply Deport",
            ],
            [
                "Medical Supplies",
                "Food & Water",
                "Command Center",
                "Shelter & Personal Care",
            ],
            [
                "Communications",
                "Information Technology",
                "Surveillance & Intelligence",
                "Public Warning Systems",
                "Power & Infrastructure"
            ],
        ];
        $types = ResourcesType::all();
        foreach($types as $type){
            if($type->id != 7){
                $index = $type->id - 1;
                foreach($categories[$index] as $category){
                    $model = new ResourcesCategory();
                    $index = $type->id - 1;
                    $model->parent_id = $type->id;
                    $model->label = $category;
                    $model->save();
                }
            }
        }
    }
}
