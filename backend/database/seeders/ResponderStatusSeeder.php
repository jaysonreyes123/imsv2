<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('responder_statuses')->insert([
            [
                "id" => 1,
                "label" => "Active"
            ],
            [
                "id" => 2,
                "label" => "InActive"
            ],
        ]);
    }
}
