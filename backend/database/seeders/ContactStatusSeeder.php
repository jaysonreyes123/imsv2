<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table("contact_statuses")->insert([
            [
                "id" => 1,
                "label" => "New",
            ],
            [
                "id" => 2,
                "label" => "Existing",
            ],
        ]);
    }
}
