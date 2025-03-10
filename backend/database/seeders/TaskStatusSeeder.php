<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tasks_statuses')->insert([
            [
                "id" => 1,
                'label' => "Not Started",
            ],
            [
                "id" => 2,
                'label' => "In Progress",
            ],
            [
                "id" => 3,
                'label' => "On hold",
            ],
            [
                "id" => 4,
                'label' => "Completed",
            ],
        ]);
    }
}
