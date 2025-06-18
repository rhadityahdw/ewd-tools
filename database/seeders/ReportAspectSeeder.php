<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportAspect;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportAspectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = Report::all();

        if ($reports->isEmpty()) {
            $this->command->warn('Tidak ada data Report. Jalankan ReportSeeder terlebih dahulu.');
            return;
        }

        // Cek apakah tabel aspect_versions ada
        if (!DB::getSchemaBuilder()->hasTable('aspect_versions')) {
            $this->command->warn('Tabel aspect_versions tidak ditemukan. Pastikan migration dan seeder untuk aspect_versions sudah dijalankan.');
            return;
        }

        $aspectVersions = DB::table('aspect_versions')->get();
        
        if ($aspectVersions->isEmpty()) {
            $this->command->warn('Tidak ada data aspect_versions. Pastikan seeder untuk aspect_versions sudah dijalankan.');
            return;
        }

        $classifications = ['safe', 'watchlist'];

        foreach ($reports as $report) {
            // Setiap report memiliki 2-4 aspects
            $numberOfAspects = fake()->numberBetween(2, 4);
            $selectedAspects = $aspectVersions->random($numberOfAspects);

            foreach ($selectedAspects as $aspectVersion) {
                ReportAspect::create([
                    'report_id' => $report->id,
                    'aspect_version_id' => $aspectVersion->id,
                    'total_score' => fake()->randomFloat(2, 0, 100), // Score 0-100 dengan 2 desimal
                    'classification' => fake()->randomElement($classifications),
                ]);
            }
        }

        $this->command->info('Report Aspects seeded successfully!');
    }
}