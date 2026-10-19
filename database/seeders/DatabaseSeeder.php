<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Example test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call all your seeders here
        $this->call([
            DoctorUnitSeeder::class,
            WardSeeder::class,
            DoctorDepartmentSeeder::class,
        ]);
    }
}
