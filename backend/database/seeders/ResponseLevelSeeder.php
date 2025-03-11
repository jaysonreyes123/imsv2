<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('response_levels')->insert([
            [
                "id" => 1,
                'label' => "Local",
            ],
            [
                "id" => 2,
                'label' => "Region",
            ],
            [
                "id" => 3,
                'label' => "National",
            ],
        ]);
    }
}
