<?php

use App\Http\Controllers\AspectController;
use App\Http\Controllers\BorrowerDetailController;
use App\Http\Controllers\BorrowerFacilityController;
use App\Models\Borrower;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Main form dengan semua data
Route::get('form', function () {
    // Load semua data yang dibutuhkan untuk 3 form
    $borrowers = Borrower::all();
    
    // Load aspect data
    $aspects = \App\Models\Aspect::with([
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
    
    // Transform aspect data
    $aspectGroups = $aspects->map(function($aspect) {
        $latestVersion = $aspect->aspectVersions->first();
        return [
            'id' => $aspect->code,
            'name' => $latestVersion ? $latestVersion->name : $aspect->name,
            'aspects' => $latestVersion ? $latestVersion->questionVersions->map(function($qv, $index) use ($aspect) {
                return [
                    'id' => $aspect->code . '.' . ($index + 1),
                    'question' => $qv->question_text,
                    'value' => '',
                    'notes' => '',
                    'question_version_id' => $qv->id,
                    'options' => $qv->options->map(function($option) {
                        return [
                            'value' => $option->option_text,
                            'score' => $option->score
                        ];
                    })
                ];
            })->toArray() : []
        ];
    })->filter(function($group) {
        return count($group['aspects']) > 0;
    });
    
    return Inertia::render('Forms/Index', [
        'borrowers' => $borrowers,
        'aspectGroups' => $aspectGroups->values()->toArray(),
        'borrower_id' => null // atau ID default
    ]);
})->name('forms.index');

// Routes untuk submit data
Route::post('forms/borrower', [BorrowerDetailController::class, 'store'])->name('forms.borrower');
Route::post('forms/facility', [BorrowerFacilityController::class, 'store'])->name('forms.facility');
Route::post('forms/aspect', [AspectController::class, 'store'])->name('forms.aspect.store');