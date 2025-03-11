<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourcesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('resources_types')->insert([
            [
                "id" => 1,
                'label' => "Personnel",
            ],
            [
                "id" => 2,
                'label' => "Vehicles",
            ],
            [
                "id" => 3,
                'label' => "Equipment",
            ],
            [
                "id" => 4,
                'label' => "Facilities",
            ],
            [
                "id" => 5,
                'label' => "Supplies",
            ],
            [
                "id" => 6,
                'label' => "Technology",
            ],
            [
                "id" => 7,
                'label' => "Others",
            ],
        ]);
    }
}
