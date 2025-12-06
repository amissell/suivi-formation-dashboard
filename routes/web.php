<?php

use App\Http\Controllers\formationController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Formation;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function() {
//     return redirect()->route('formations.index');
// });
// Route::get('/dashboard', function () {
//     return view('dashboard'); // smiya dyal Blade file
// });
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/students/export-pdf', [StudentController::class, 'exportPdf'])->name('students.exportPdf');

Route::resource('formations', controller: formationController::class);
Route::resource('students', studentController::class);
// Route::resource('formations', FormationController::class);

Route::get('/formations/{formation}/price', function (Formation $formation) {
    return response()->json(['price' => (float) $formation->price]);
});

