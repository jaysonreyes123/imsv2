<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('caller_types')->insert([
            [
                "id" => 1,
                "label" => "Prank Caller"
            ],
            [
                "id" => 2,
                "label" => "Relative"
            ],
            [
                "id" => 3,
                "label" => "Reporter"
            ],
            [
                "id" => 4,
                "label" => "Victim"
            ],
        ]);
    }
}
