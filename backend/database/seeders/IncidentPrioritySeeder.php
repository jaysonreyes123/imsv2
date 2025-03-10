<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('incident_priorities')->insert([
            [
                "id" => 1,
                "label" => "Critical"
            ],
            [
                "id" => 2,
                "label" => "High"
            ],
            [
                "id" => 3,
                "label" => "Medium"
            ],
            [
                "id" => 4,
                "label" => "Low"
            ],
            [
                "id" => 5,
                "label" => "None"
            ],
        ]);
    }
}
