<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            ModuleSeeder::class,
            userRoleSeeder::class,
            RoleSeeder::class,
            userSeeder::class,
            IncidentTypeSeeder::class,
            IncidentStatusSeeder::class,
            IncidentPrioritySeeder::class,
            ResponderTypeSeeder::class,
            TaskStatusSeeder::class,
            ResourcesStatusSeeder::class,
            ResourcesTypeSeeder::class,
            ResourcesCategorySeeder::class,
            RiskLevelSeeder::class,
            ResponseLevelSeeder::class,
            ContactStatusSeeder::class,
            IncidentSeeder::class,
            ResourcesSeeder::class,
            CallerTypeSeeder::class,
            StatusSeeder::class,
            ResponderStatusSeeder::class
        ]);
    }
}
