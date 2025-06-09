<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PbxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('pbxes')->insert([
            [
                "name"      => "Yeastar",
                "username"  => "IUQ0psJ7RmLQZquhm0AobRJAxrTUDPKP",
                "password"  => "pWGF7pjTghGcrImRtIp8GgdxGIkapvDt",
                "api"       => "https://localhost.sgycm.yeastarcloud.com/openapi/v1.0",
                "status"    => 1
            ],
            [
                "name"      => "3CX",
                "username"  => "VSBi5AGKVu2Pry4jbfcHktuK6CdrGpUS",
                "password"  => "2twdhKg7phju7ezIN3jSKBBhs5XrVO2F",
                "api"       => "https://192.168.1.150:8088/openapi/v1.0",
                "status"    => 0
            ]
        ]);
    }
}
