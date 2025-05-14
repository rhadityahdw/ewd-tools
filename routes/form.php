<?php

use App\Http\Controllers\BorrowerDetailController;
use App\Http\Controllers\BorrowerFacilityController;
use App\Models\Borrower;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('forms')->group(function () {
    Route::get('info', function () {

        $borrowers = Borrower::all();
    
        return Inertia::render('InformationForm', [
            'borrowers' => $borrowers,
        ]);
    })->name('forms.borrower');
    
    Route::post('info', [BorrowerDetailController::class, 'store']);
    
    Route::get('facility', function () {
        return Inertia::render('FacilityForm', [
            'borrower_id' => session('borrower_id'),
        ]);
    })->name('forms.facility');
    
    Route::post('facility', [BorrowerFacilityController::class,'store']);
    
    Route::get('aspect', function () {
        return Inertia::render('AspectForm');
    })->name('forms.aspect');
});