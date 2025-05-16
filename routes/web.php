<?php

use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\AspectController;
use App\Http\Controllers\PeriodController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);

Route::resource('borrowers', BorrowerController::class);

Route::resource('templates', TemplateController::class);

Route::resource('aspects', AspectController::class);

Route::resource('templates', TemplateController::class);

Route::middleware(['auth'])->group(function () {
    Route::resource('periods', PeriodController::class);

    Route::post('periods/{period}/start', [PeriodController::class, 'start'])->name('periods.start');
    Route::post('periods/{period}/end', [PeriodController::class,'stop'])->name('periods.stop');
    Route::post('periods/{period}/extend',[PeriodController::class,'extend'])->name('periods.extend');
});

require __DIR__.'/form.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
