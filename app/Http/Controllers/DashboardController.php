<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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
        $startDate = Carbon::now()->subDays(7);
        $studentsPerDay = Student::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
            return view('dashboard',    compact('stats', 'recentFormations', 'recentStudents', 'studentsPerDay'));
    }
}
