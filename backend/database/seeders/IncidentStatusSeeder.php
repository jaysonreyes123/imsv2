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
                'label' => "New",
            ],
            [
                "id" => 2,
                'label' => "Open",
            ],
            [
                "id" => 3,
                'label' => "In Progress",
            ],
            [
                "id" => 4,
                'label' => "On Hold",
            ],
            [
                "id" => 5,
                'label' => "Resolved",
            ],
            [
                "id" => 6,
                'label' => "Closed",
            ],
            [
                "id" => 7,
                'label' => "Cancelled",
            ],
        ]);
    }
}
