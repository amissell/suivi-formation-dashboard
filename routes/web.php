<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\StudentController;
use App\Models\Formation;



// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/students/export-excel', [StudentController::class, 'exportExcel'])
    ->name('students.export');

Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

// Resources
Route::resource('formations', FormationController::class);
Route::resource('students', StudentController::class)->except(['show']);

// Get formation price (AJAX)
Route::get('/formations/{formation}/price', function (Formation $formation) {
    return response()->json([
        'price' => (float) $formation->price
    ]);
});

Route::post('/students/reset', [StudentController::class, 'resetStudents'])->name('students.reset');

Route::post('/students/reset-import', [StudentController::class, 'resetAndImport'])
    ->name('students.resetImport');
