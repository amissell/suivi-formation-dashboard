<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Student;

Route::get('/students/search', function (Request $request) {
    $query = $request->get('q', '');

    return Student::with('formation')
        ->where('name', 'like', "%{$query}%")
        ->orWhere('cin', 'like', "%{$query}%")
        ->orWhere('phone', 'like', "%{$query}%")
        ->limit(10)
        ->get()
        ->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'formation_name' => $s->formation->name ?? '',
            'city' => $s->city,
        ]);
});
