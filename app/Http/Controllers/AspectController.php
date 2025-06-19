<?php

namespace App\Http\Controllers;

use App\Models\Aspect;
use App\Models\AspectVersion;
use App\Models\Question;
use App\Models\QuestionVersion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AspectController extends Controller
{
    public function index()
    {
        // Load aspects with their active versions and question versions
        $aspects = Aspect::with([
            'aspectVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version', 'desc')
                      ->limit(1);
            },
            'aspectVersions.questionVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version_number', 'desc');
            },
            'aspectVersions.questionVersions.options'
        ])->get();

        // Transform data for frontend
        $transformedAspects = $aspects->map(function($aspect) {
            $latestVersion = $aspect->aspectVersions->first();
            return [
                'id' => $aspect->id,
                'name' => $latestVersion ? $latestVersion->name : $aspect->name,
                'code' => $aspect->code,
                'description' => $aspect->description,
                'version' => $latestVersion ? $latestVersion->version : 1,
                'questions_count' => $latestVersion ? $latestVersion->questionVersions->count() : 0,
                'questions' => $latestVersion ? $latestVersion->questionVersions->map(function($qv) {
                    return [
                        'id' => $qv->id,
                        'question_text' => $qv->question_text,
                        'weight' => $qv->weight,
                        'max_score' => $qv->max_score,
                        'min_score' => $qv->min_score,
                        'is_mandatory' => $qv->is_mandatory,
                        'options' => $qv->options->map(function($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'score' => $option->score
                            ];
                        })
                    ];
                }) : []
            ];
        });

        return Inertia::render('aspects/Index', [
            'aspects' => $transformedAspects,
        ]);
    }

    public function show(Aspect $aspect)
    {
        // Load aspect with its latest version and question versions
        $aspect->load([
            'aspectVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version', 'desc')
                      ->limit(1);
            },
            'aspectVersions.questionVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version_number', 'desc');
            },
            'aspectVersions.questionVersions.options'
        ]);

        $latestVersion = $aspect->aspectVersions->first();
        
        $transformedAspect = [
            'id' => $aspect->id,
            'name' => $latestVersion ? $latestVersion->name : $aspect->name,
            'code' => $aspect->code,
            'description' => $aspect->description,
            'version' => $latestVersion ? $latestVersion->version : 1,
            'questions' => $latestVersion ? $latestVersion->questionVersions->map(function($qv) {
                return [
                    'id' => $qv->id,
                    'question_text' => $qv->question_text,
                    'weight' => $qv->weight,
                    'max_score' => $qv->max_score,
                    'min_score' => $qv->min_score,
                    'is_mandatory' => $qv->is_mandatory,
                    'version_number' => $qv->version_number,
                    'options' => $qv->options->map(function($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                            'score' => $option->score
                        ];
                    })
                ];
            }) : []
        ];

        return Inertia::render('aspects/Show', [
            'aspect' => $transformedAspect,
        ]);
    }

    public function create()
    {
        return Inertia::render('aspects/Create', [
            'questionTypes' => [
                ['value' => 'multiple_choice', 'label' => 'Pilihan Ganda'],
                ['value' => 'text', 'label' => 'Teks'],
                ['value' => 'number', 'label' => 'Angka'],
            ]
        ]);
    }

    public function form()
    {
        // Load all aspects with their latest versions and questions for the form
        $aspects = Aspect::with([
            'aspectVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version', 'desc')
                      ->limit(1);
            },
            'aspectVersions.questionVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version_number', 'desc');
            },
            'aspectVersions.questionVersions.options'
        ])->get();
    
        // Transform data for the form
        $aspectGroups = $aspects->map(function($aspect) {
            $latestVersion = $aspect->aspectVersions->first();
            return [
                'id' => $aspect->code, // Use code as ID for form
                'name' => $latestVersion ? $latestVersion->name : $aspect->name,
                'aspects' => $latestVersion ? $latestVersion->questionVersions->map(function($qv, $index) use ($aspect) {
                    return [
                        'id' => $aspect->code . '.' . ($index + 1),
                        'question' => $qv->question_text,
                        'value' => '', // Default empty value for form
                        'notes' => '',
                        'question_version_id' => $qv->id,
                        'options' => $qv->options->map(function($option) {
                            return [
                                'text' => $option->option_text,
                                'value' => $option->option_text,
                                'score' => $option->score
                            ];
                        })
                    ];
                })->toArray() : []
            ];
        })->filter(function($group) {
            return count($group['aspects']) > 0; // Only include groups with questions
        });
    
        return Inertia::render('Forms/AspectForm', [
            'aspectGroups' => $aspectGroups->values()->toArray(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'questions.*.max_score' => ['nullable', 'numeric', 'min:0'],
            'questions.*.min_score' => ['nullable', 'numeric', 'min:0'],
            'questions.*.is_mandatory' => ['boolean'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*.option_text' => ['required_with:questions.*.options', 'string'],
            'questions.*.options.*.score' => ['required_with:questions.*.options', 'numeric'],
        ]);
    
        // Generate unique code
        $code = strtoupper(substr($validated['name'], 0, 3)) . '_' . time();
        
        $aspect = Aspect::create([
            'name' => $validated['name'],
            'code' => $code,
            'description' => $validated['description'],
        ]);
    
        // Create aspect version
        $aspectVersion = AspectVersion::create([
            'aspect_id' => $aspect->id,
            'version' => 1,
            'name' => $validated['name'],
            'effective_from' => Carbon::now()
        ]);
    
        // Create questions and question versions
        foreach ($validated['questions'] as $questionData) {
            $question = Question::create([]);
            
            $questionVersion = QuestionVersion::create([
                'question_id' => $question->id,
                'aspect_version_id' => $aspectVersion->id,
                'version_number' => 1,
                'question_text' => $questionData['question_text'],
                'weight' => $questionData['weight'] ?? 1,
                'max_score' => $questionData['max_score'] ?? 100,
                'min_score' => $questionData['min_score'] ?? 0,
                'is_mandatory' => $questionData['is_mandatory'] ?? false,
                'effective_from' => Carbon::now()
            ]);
    
            // Create question options if provided
            if (isset($questionData['options']) && is_array($questionData['options'])) {
                foreach ($questionData['options'] as $optionData) {
                    $questionVersion->options()->create([
                        'option_text' => $optionData['option_text'],
                        'score' => $optionData['score'],
                        'effective_from' => Carbon::now()
                    ]);
                }
            }
        }
    
        return redirect()->route('aspects.index')->with('success', 'Aspek berhasil dibuat.');
    }

    public function edit(Aspect $aspect)
    {
        // Load for editing - similar to show but for edit form
        $aspect->load([
            'aspectVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version', 'desc')
                      ->limit(1);
            },
            'aspectVersions.questionVersions' => function($query) {
                $query->where('effective_from', '<=', now())
                      ->orderBy('version_number', 'desc');
            },
            'aspectVersions.questionVersions.options'
        ]);

        $latestVersion = $aspect->aspectVersions->first();
        
        $transformedAspect = [
            'id' => $aspect->id,
            'name' => $latestVersion ? $latestVersion->name : $aspect->name,
            'code' => $aspect->code,
            'description' => $aspect->description,
            'questions' => $latestVersion ? $latestVersion->questionVersions->map(function($qv) {
                return [
                    'id' => $qv->id,
                    'question_text' => $qv->question_text,
                    'weight' => $qv->weight,
                    'max_score' => $qv->max_score,
                    'min_score' => $qv->min_score,
                    'is_mandatory' => $qv->is_mandatory,
                    'options' => $qv->options->map(function($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text,
                            'score' => $option->score
                        ];
                    })
                ];
            }) : []
        ];

        return Inertia::render('aspects/Edit', [
            'aspect' => $transformedAspect,
        ]);
    }

    public function update(Request $request, Aspect $aspect)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['nullable', 'exists:question_versions,id'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'questions.*.max_score' => ['nullable', 'numeric', 'min:0'],
            'questions.*.min_score' => ['nullable', 'numeric', 'min:0'],
            'questions.*.is_mandatory' => ['boolean'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*.option_text' => ['required_with:questions.*.options', 'string'],
            'questions.*.options.*.score' => ['required_with:questions.*.options', 'numeric'],
        ]);
    
        // Get current version number
        $currentVersion = $aspect->aspectVersions()->orderBy('version', 'desc')->first();
        $newVersionNumber = $currentVersion ? $currentVersion->version + 1 : 1;
    
        // Create new aspect version
        $aspectVersion = AspectVersion::create([
            'aspect_id' => $aspect->id,
            'version' => $newVersionNumber,
            'name' => $validated['name'],
            'effective_from' => Carbon::now()
        ]);
    
        // Update aspect basic info
        $aspect->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
    
        // Create new question versions
        foreach ($validated['questions'] as $questionData) {
            // Create new question if it doesn't exist
            $question = Question::create([]);
            
            $questionVersion = QuestionVersion::create([
                'question_id' => $question->id,
                'aspect_version_id' => $aspectVersion->id,
                'version_number' => 1,
                'question_text' => $questionData['question_text'],
                'weight' => $questionData['weight'] ?? 1,
                'max_score' => $questionData['max_score'] ?? 100,
                'min_score' => $questionData['min_score'] ?? 0,
                'is_mandatory' => $questionData['is_mandatory'] ?? false,
                'effective_from' => Carbon::now()
            ]);
    
            // Create question options if provided
            if (isset($questionData['options']) && is_array($questionData['options'])) {
                foreach ($questionData['options'] as $optionData) {
                    $questionVersion->options()->create([
                        'option_text' => $optionData['option_text'],
                        'score' => $optionData['score'],
                        'effective_from' => Carbon::now()
                    ]);
                }
            }
        }
    
        return redirect()->route('aspects.index')->with('success', 'Aspek berhasil diperbarui.');
    }

    public function destroy(Aspect $aspect)
    {
        $aspect->delete();
        return redirect()->route('aspects.index')
            ->with('success', 'Aspek berhasil dihapus.');
    }
}
