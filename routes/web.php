<?php

use App\Http\Controllers\formationController;
use App\Http\Controllers\studentController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function() {
    return redirect()->route('formations.index');
});


Route::resource('formations', formationController::class);
Route::resource('students', studentController::class);

