<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table("user_roles")->insert([
            [
                "id"    => 1,
                "label" => "Administrator"
            ],

            [
                "id"    => 2,
                "label" => "Supervisor"
            ],
            [
                "id"    => 3,
                "label" => "Calltaker"
            ],
            [
                "id"    => 4,
                "label" => "Dispatcher"
            ],
        ]);
    }
}
