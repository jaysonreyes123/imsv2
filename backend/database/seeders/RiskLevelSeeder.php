<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('risk_levels')->insert([
            [
                "id" => 1,
                'label' => "Low",
            ],
            [
                "id" => 2,
                'label' => "Moderate",
            ],
            [
                "id" => 3,
                'label' => "High",
            ],
            [
                "id" => 4,
                'label' => "Critical",
            ],
        ]);
    }
}
