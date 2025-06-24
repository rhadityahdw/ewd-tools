<?php

namespace App\Services;

use App\Models\Aspect;
use App\Models\AspectVersion;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class AspectBuilderService
{
    public function createAspectWithQuestions(array $data): Aspect
    {
        return DB::transaction(function () use ($data) {
            $aspect = Aspect::create(['code' => $data['code']]);
            
            $aspectVersion = $aspect->versions()->create([
                'version_number' => 1,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'effective_from' => now(),
            ]);

            if (!empty($data['questions'])) {
                $this->createQuestion($aspectVersion, $data['questions']);
            }

            return $aspect;
        });
    }

    public function updateAspectWithQuestions(Aspect $aspect, array $data): Aspect
    {
        return DB::transaction(function () use ($aspect, $data) {
            $aspect->update(['code' => $data['code']]);

            $aspectVersion = $aspect->versions()->create([
                'version_number' => $aspect->versions()->max('version_number') + 1,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'effective_from' => now(),
            ]);

            if (!empty($data['questions'])) {
                $this->createQuestion($aspectVersion, $data['questions']);
            }

            return $aspect;
        });
    }

    private function createQuestion(AspectVersion $aspectVersion, array $questionsData): void
    {
        foreach ($questionsData as $data) {
            $question = Question::create([]);

            $questionVersion = $question->versions()->create([
                    'aspect_version_id' => $aspectVersion->id,
                    'version_number' => 1,
                    'question_text' => $data['question_text'],
                    'weight' => $data['weight'],
                    'max_score' => $data['max_score'],
                    'min_score' => $data['min_score'],
                    'is_mandatory' => $data['is_mandatory'],
                    'effective_from' => now(),
                ]);

                if (!empty($data['options'])) {
                    $options = array_map(function ($opt) {
                        return [
                            'option_text' => $opt['option_text'],
                            'score' => $opt['score'],
                            'effective_from' => now(),
                        ];
                    }, $data['options']);
                    $questionVersion->options()->createMany($options);
                }

                if (!empty($data['visibility_rules'])) {
                    $question->visibilityRules()->createMany($data['visibility_rules']);
                }
        }
    }
}