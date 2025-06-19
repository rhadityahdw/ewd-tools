<?php

namespace Database\Seeders;

use App\Models\Aspect;
use App\Models\AspectVersion;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionVersion;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AspectQuestionSeeder extends Seeder
{
    public function run()
    {
        // Create sample template first
        $template = Template::create([
            'name' => 'Limit >10M',
        ]);

        // Create sample aspects
        $aspectsData = [
            [
                'name' => 'Aspek Hukum',
                'code' => 'A',
                'description' => 'Assessment of borrower financial performance and stability'
            ],
            [
                'name' => 'Aspek Manajemen',
                'code' => 'B',
                'description' => 'Evaluation of management team competency and experience'
            ],
            [
                'name' => 'Aspek Teknis Produksi',
                'code' => 'C',
                'description' => 'Analysis of market position and competitive advantage'
            ],
            [
                'name' => 'Aspek Agunan/Jaminan',
                'code' => 'D',
                'description' => 'Assessment of operational processes and efficiency'
            ],
            [
                'name' => 'Aspek Keuangan',
                'code' => 'E',
                'description' => 'Assessment of operational processes and efficiency'
            ],
            [
                'name' => 'Aspek Pemasaran',
                'code' => 'F',
                'description' => 'Assessment of operational processes and efficiency'
            ],
            [
                'name' => 'Aspek Amdal',
                'code' => 'G',
                'description' => 'Assessment of operational processes and efficiency'
            ],
            [
                'name' => 'Aspek Lainnya',
                'code' => 'H',
                'description' => 'Assessment of operational processes and efficiency'
            ]
        ];

        $effectiveDate = Carbon::now();

        foreach ($aspectsData as $index => $aspectData) {
            // Create aspect
            $aspect = Aspect::create($aspectData);
            
            // Create aspect version (simplified - only required fields)
            $aspectVersion = AspectVersion::create([
                'aspect_id' => $aspect->id,
                'version' => 1,
                'name' => $aspectData['name'],
                'effective_from' => $effectiveDate
            ]);

            // Link aspect version to template
            $template->aspectVersions()->attach($aspectVersion->id, [
                'weight' => 25 // Equal weight for all aspects (25% each)
            ]);
            
            // Create questions for each aspect
            $this->createQuestionsForAspect($aspect, $aspectVersion, $effectiveDate);
        }
    }

    private function createQuestionsForAspect(Aspect $aspect, AspectVersion $aspectVersion, Carbon $effectiveDate)
    {
        $questionTemplates = $this->getQuestionTemplates($aspect->code);
        
        foreach ($questionTemplates as $template) {
            // Create base question (empty container)
            $question = Question::create([]);

            // Create question version
            $questionVersion = QuestionVersion::create([
                'question_id' => $question->id,
                'aspect_version_id' => $aspectVersion->id,
                'version_number' => 1,
                'question_text' => $template['text'],
                'weight' => $template['weight'],
                'max_score' => $template['max_score'],
                'min_score' => $template['min_score'],
                'is_mandatory' => $template['is_mandatory'],
                'effective_from' => $effectiveDate
            ]);

            // Create question options if provided
            if (isset($template['options'])) {
                foreach ($template['options'] as $optionData) {
                    QuestionOption::create([
                        'question_version_id' => $questionVersion->id,
                        'option_text' => $optionData['text'],
                        'score' => $optionData['score'],
                        'effective_from' => $effectiveDate
                    ]);
                }
            }
        }
    }

    private function getQuestionTemplates($aspectCode)
    {
        $templates = [
            'A' => [
                [
                    'text' => 'Apakah legalitas dan perijinan nasabah masih berlaku dan sesuai ketentuan?',
                    'weight' => 0.15,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Apakah ada tuntuan/masalah hukum dari internal/eksternal/pihak ketiga yang berdampak signifikan terhadap kondisi keuangan perusahaan?',
                    'weight' => 0.20,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Ya', 'score' => -100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Apakah ada permasalahan (pemogokan, perselisihan tenaga kerja, pengurangan tenaga kerja, dll) antara manajemen/perusahaan dengan tenaga kerja yang berpotensi menggangu kelangsungan usaha debitur?',
                    'weight' => 0.20,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Ya', 'score' => -100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Apakah justifikasi ekspornya masih terpenuhi sesuai dengan ketentuan?',
                    'weight' => 0.15,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Apakah debitur menggunakan KAP / KJPP / Asuransi / Notaris yang bukan Rekanan atau terdapat pembekuan izin/pengenaan sanksi dari regulator atau tidak mendapat izin OJK dan Kemenkeu atau QR tidak sesuai?',
                    'weight' => 0.15,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Jika debitur belum mengganti KAP / KJPP / Notaris Non Rekanan, apakah penggunaan KAP / KJPP / Notaris Non Rekanan tersebut telah mendapatkan Persetujuan dari direksi atau pemegang kewenangan pemutus kredit sesuai ketentuan di SPO Pembiayaan.',
                    'weight' => 0.0,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ]
            ],
            'B' => [
                [
                    'text' => 'Apakah ada isu negatif tentang perusahaan / pemilik / pengurus perusahaan / afiliasi?',
                    'weight' => 0.75,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
                [
                    'text' => 'Apakah ada perubahan pemegang saham dan pengurus yang tidak memenuhi ketentuan dalam Perjanjian Kredit ?',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Ya', 'score' => 100.00],
                        ['text' => 'Tidak', 'score' => 0.00]
                    ]
                ],
            ],
            'MKT_POS' => [
                [
                    'text' => 'What is the borrower\'s market share in their primary market?',
                    'weight' => 0.35,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Market leader (>30%)', 'score' => 100.00],
                        ['text' => 'Strong position (15-30%)', 'score' => 80.00],
                        ['text' => 'Moderate position (5-15%)', 'score' => 60.00],
                        ['text' => 'Small player (<5%)', 'score' => 40.00]
                    ]
                ],
                [
                    'text' => 'How intense is the competition in the borrower\'s market?',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Low competition', 'score' => 100.00],
                        ['text' => 'Moderate competition', 'score' => 70.00],
                        ['text' => 'High competition', 'score' => 50.00],
                        ['text' => 'Very intense competition', 'score' => 30.00]
                    ]
                ],
                [
                    'text' => 'What is the borrower\'s competitive advantage?',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Strong unique advantages', 'score' => 100.00],
                        ['text' => 'Some competitive advantages', 'score' => 75.00],
                        ['text' => 'Limited advantages', 'score' => 50.00],
                        ['text' => 'No clear advantages', 'score' => 25.00]
                    ]
                ],
                [
                    'text' => 'How diversified is the borrower\'s customer base?',
                    'weight' => 0.15,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Highly diversified', 'score' => 100.00],
                        ['text' => 'Moderately diversified', 'score' => 75.00],
                        ['text' => 'Somewhat concentrated', 'score' => 50.00],
                        ['text' => 'Highly concentrated', 'score' => 25.00]
                    ]
                ]
            ],
            'OPR_EFF' => [
                [
                    'text' => 'How would you rate the borrower\'s operational efficiency?',
                    'weight' => 0.30,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Highly efficient operations', 'score' => 100.00],
                        ['text' => 'Good operational efficiency', 'score' => 80.00],
                        ['text' => 'Average efficiency', 'score' => 60.00],
                        ['text' => 'Poor operational efficiency', 'score' => 30.00]
                    ]
                ],
                [
                    'text' => 'What is the status of the borrower\'s technology adoption?',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Advanced technology adoption', 'score' => 100.00],
                        ['text' => 'Modern technology systems', 'score' => 80.00],
                        ['text' => 'Basic technology systems', 'score' => 60.00],
                        ['text' => 'Outdated technology', 'score' => 30.00]
                    ]
                ],
                [
                    'text' => 'How effective are the borrower\'s quality control processes?',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => true,
                    'options' => [
                        ['text' => 'Excellent quality control', 'score' => 100.00],
                        ['text' => 'Good quality processes', 'score' => 80.00],
                        ['text' => 'Basic quality control', 'score' => 60.00],
                        ['text' => 'Poor quality control', 'score' => 30.00]
                    ]
                ],
                [
                    'text' => 'What is the borrower\'s supply chain reliability?',
                    'weight' => 0.20,
                    'max_score' => 100.00,
                    'min_score' => 0.00,
                    'is_mandatory' => false,
                    'options' => [
                        ['text' => 'Highly reliable supply chain', 'score' => 100.00],
                        ['text' => 'Generally reliable', 'score' => 80.00],
                        ['text' => 'Moderately reliable', 'score' => 60.00],
                        ['text' => 'Unreliable supply chain', 'score' => 30.00]
                    ]
                ]
            ]
        ];

        return $templates[$aspectCode] ?? [];
    }
}