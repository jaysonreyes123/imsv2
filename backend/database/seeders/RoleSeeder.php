<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('roles')->insert([
            [
                "id" => 1,
                "parent_id" => 1,
                "label" => "Dispatcher"
            ],
            [
                "id" => 2,
                "parent_id" => 2,
                "label" => "Calltaker"
            ],
            [
                "id" => 3,
                "parent_id" => 2,
                "label" => "Dispatcher"
            ],
        ]);
    }
}
