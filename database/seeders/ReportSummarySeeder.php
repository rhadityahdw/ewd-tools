<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportSummary;
use Illuminate\Database\Seeder;

class ReportSummarySeeder extends Seeder
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

        $classifications = ['safe', 'watchlist'];
        $collectibilityOptions = [1, 2, 3, 4, 5];
        
        $businessNotes = [
            'Debitur menunjukkan kinerja yang baik dengan pembayaran tepat waktu.',
            'Terdapat sedikit keterlambatan pembayaran namun masih dalam batas wajar.',
            'Debitur mengalami kesulitan keuangan sementara akibat kondisi pasar.',
            'Kinerja debitur stabil dengan prospek bisnis yang positif.',
            'Diperlukan monitoring lebih ketat terhadap kondisi keuangan debitur.',
        ];

        $reviewerNotes = [
            'Review menyeluruh telah dilakukan sesuai prosedur standar.',
            'Diperlukan verifikasi tambahan untuk beberapa aspek keuangan.',
            'Dokumentasi lengkap dan sesuai dengan ketentuan yang berlaku.',
            'Rekomendasi untuk melakukan review ulang dalam 3 bulan ke depan.',
            'Semua aspek telah dievaluasi dengan hasil memuaskan.',
        ];

        foreach ($reports as $report) {
            $isOverride = fake()->boolean(20); // 20% kemungkinan override
            
            ReportSummary::create([
                'report_id' => $report->id,
                'final_classification' => fake()->randomElement($classifications),
                'indicative_collectibility' => fake()->randomElement($collectibilityOptions),
                'is_override' => $isOverride,
                'override_reason' => $isOverride ? 'Override dilakukan berdasarkan pertimbangan khusus dari reviewer senior.' : null,
                'business_notes' => fake()->randomElement($businessNotes),
                'reviewer_notes' => fake()->randomElement($reviewerNotes),
                'summary_generated_at' => $report->created_at->addHours(fake()->numberBetween(1, 48)),
            ]);
        }

        $this->command->info('Report Summaries seeded successfully!');
    }
}