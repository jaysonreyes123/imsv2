<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('incident_statuses')->insert([
            [
                "id" => 1,
                'label' => "Open",
            ],
            [
                "id" => 2,
                'label' => "In Progress",
            ],
            [
                "id" => 3,
                'label' => "Resolved",
            ],
            [
                "id" => 4,
                'label' => "Closed",
            ],
            [
                "id" => 5,
                'label' => "On Hold",
            ],
        ]);
    }
}
