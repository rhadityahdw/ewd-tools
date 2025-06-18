<?php

namespace Database\Seeders;

use App\Models\Borrower;
use App\Models\Period;
use App\Models\Report;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data prerequisite
        $borrowers = Borrower::all();
        $templates = Template::all();
        $periods = Period::all();
        $users = User::all();

        if ($borrowers->isEmpty() || $templates->isEmpty() || $periods->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Pastikan data Borrower, Template, Period, dan User sudah ada sebelum menjalankan ReportSeeder');
            return;
        }

        $reports = [
            [
                'borrower_id' => $borrowers->random()->id,
                'template_id' => $templates->random()->id,
                'period_id' => $periods->random()->id,
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'borrower_id' => $borrowers->random()->id,
                'template_id' => $templates->random()->id,
                'period_id' => $periods->random()->id,
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'borrower_id' => $borrowers->random()->id,
                'template_id' => $templates->random()->id,
                'period_id' => $periods->random()->id,
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'borrower_id' => $borrowers->random()->id,
                'template_id' => $templates->random()->id,
                'period_id' => $periods->random()->id,
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'borrower_id' => $borrowers->random()->id,
                'template_id' => $templates->random()->id,
                'period_id' => $periods->random()->id,
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];

        foreach ($reports as $reportData) {
            Report::create($reportData);
        }

        $this->command->info('Reports seeded successfully!');
    }
}