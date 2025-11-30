<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_formations' => Formation::count(), 
            'graduated' => 'N/A', 
            'success_rate' => 'N/A', 
        ];

        $recentFormations = Formation::latest()->take(3)->get();
        $recentStudents = Student::latest()->take(3)->get();

        return view('dashboard', compact('stats', 'recentFormations', 'recentStudents'));
    }
}
