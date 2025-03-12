<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use App\Models\UserPrivilege;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'firstname' => 'Admin',
                'email' => 'admin@test.com',
                'password' => bcrypt('admin'),
                'user_roles' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'firstname' => 'Jayson',
                'email' => 'jayson.reyes@microbizone.com',
                'password' => bcrypt('admin'),
                'user_roles' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'firstname' => 'Andrea',
                'email' => 'andrea@microbizone.com',
                'password' => bcrypt('password'),
                'user_roles' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'firstname' => 'Chloe',
                'email' => 'chloe.microbizintern@gmail.com',
                'password' => bcrypt('chloeintern#1'),
                'user_roles' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]

        ]);

        $users = User::all();
        $modules = Module::whereIn('presence',[1,3])->get();
        foreach($users as $user){
            foreach($modules as $module){
                $user_privileges = new UserPrivilege();
                $user_privileges->user_id = $user->id;
                $user_privileges->module = $module->id;
                $user_privileges->status = 1;
                $user_privileges->save();
            }
        }
    }
}
