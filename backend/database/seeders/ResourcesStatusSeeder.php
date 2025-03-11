<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourcesStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('resources_statuses')->insert([
            [
                "id" => 1,
                'label' => "Available",
            ],
            [
                "id" => 2,
                'label' => "Assigned",
            ],
            [
                "id" => 3,
                'label' => "Out of Service",
            ],
        ]);
    }
}
