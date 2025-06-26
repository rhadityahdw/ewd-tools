<?php

use App\Http\Controllers\AspectController;
use App\Http\Controllers\BorrowerDetailController;
use App\Http\Controllers\BorrowerFacilityController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Main form dengan semua data
Route::get('form', [FormController::class, 'index'])->name('forms.index');

// Routes untuk submit data
Route::post('forms/borrower', [BorrowerDetailController::class, 'store'])->name('forms.borrower');
Route::post('forms/facility', [BorrowerFacilityController::class, 'store'])->name('forms.facility');
Route::post('forms/aspect', [AspectController::class, 'store'])->name('forms.aspect.store');

// Route untuk submit semua data form
Route::post('/forms/submit', [FormController::class, 'submitAll'])->name('forms.submit');

// Route untuk update notes di summary
Route::put('/reports/{report}/notes', [ReportController::class, 'updateNotes'])->name('reports.update-notes');

// Route untuk export PDF
Route::get('/reports/{report}/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export-pdf');