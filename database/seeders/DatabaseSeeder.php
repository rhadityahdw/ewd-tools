<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            DivisionSeeder::class,
            UserSeeder::class,
            AspectQuestionSeeder::class, // Add this line

            // Tambahkan seeder report di sini
            // ReportSeeder::class,
            // ReportSummarySeeder::class,
            // ReportAspectSeeder::class,
        ]);
    }
}
