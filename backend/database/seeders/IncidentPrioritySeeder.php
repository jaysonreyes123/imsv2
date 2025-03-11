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
                "label" => "Low"
            ],
            [
                "id" => 2,
                "label" => "Medium"
            ],
            [
                "id" => 3,
                "label" => "High"
            ],
            [
                "id" => 4,
                "label" => "Critical"
            ],
            [
                "id" => 5,
                "label" => "None"
            ],
        ]);
    }
}
