<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\StudentController;
use App\Models\Formation;

// Redirect root to dashboard (will auto-redirect to login if not authenticated)
Route::redirect('/', '/dashboard');

// ALL routes protected by auth middleware
Route::middleware(['auth'])->group(function () {
    
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Students
    Route::get('/students/export-excel', [StudentController::class, 'exportExcel'])
        ->name('students.export');
    Route::post('/students/import', [StudentController::class, 'import'])
        ->name('students.import');
    Route::post('/students/reset', [StudentController::class, 'resetStudents'])
        ->name('students.reset');
    Route::post('/students/reset-import', [StudentController::class, 'resetAndImport'])
        ->name('students.resetImport');
    Route::resource('students', StudentController::class)->except(['show']);

    // Formations
    Route::resource('formations', FormationController::class);
    Route::get('/formations/{formation}/price', function (Formation $formation) {
        return response()->json([
            'price' => (float) $formation->price
        ]);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit'); // create this view if it doesn't exist
    })->name('profile.edit');
});


// Include Breeze auth routes
require __DIR__.'/auth.php';
