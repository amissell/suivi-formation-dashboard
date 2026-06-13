<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    /**
     * Display a listing of students with filters
     */
    public function index(Request $request)
    {
        // Save current filters to session for preservation after edit/update
        $filters = $request->only(['search', 'formation_id', 'city', 'payment_status', 'date_from', 'date_to']);
        session(['student_filters' => array_filter($filters)]);

        // Start query with eager loading (prevents N+1 queries)
        $query = Student::with(['formation', 'groups.formation', 'attendances.seance']);

        // ============================================
        // SEARCH FILTER
        // ============================================
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            // Search across multiple fields
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('cin', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // ============================================
        // FORMATION FILTER
        // ============================================
        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->formation_id);
        }

        // ============================================
        // CITY FILTER
        // ============================================
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // ============================================
        // PAYMENT STATUS FILTER
        // ============================================
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('payment_remaining', '<=', 0);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('payment_remaining', '>', 0);
            }
        }

        // ============================================
        // DATE RANGE FILTER
        // ============================================
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        // ============================================
        // SORTING & PAGINATION
        // ============================================
        $students = $query->latest()->paginate(15)->withQueryString();
        
        // Get data for dropdowns
        $formations = Formation::orderBy('name')->get();
        $cities = Student::whereNotNull('city')
                        ->distinct()
                        ->pluck('city')
                        ->filter()
                        ->sort()
                        ->values();

        return view('students.index', compact('students', 'formations', 'cities'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cin' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'formation_id' => 'required|exists:formations,id',
            'start_date' => 'required|date',
            'engagement' => 'required|numeric|min:0',
            'payment_done' => 'nullable|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        // Calculate remaining payment
        $data['payment_done'] = $data['payment_done'] ?? 0;
        $data['payment_remaining'] = $data['engagement'] - $data['payment_done'];
        
        Student::create($data);
        
        // Redirect with preserved filters from session
        $filters = session('student_filters', []);
        return redirect()->route('students.index', $filters)
            ->with('success', 'Candidat ajouté avec succès!');
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cin' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'formation_id' => 'required|exists:formations,id',
            'start_date' => 'required|date',
            'engagement' => 'required|numeric|min:0',
            'payment_done' => 'nullable|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        // Calculate remaining payment
        $data['payment_done'] = $data['payment_done'] ?? 0;
        $data['payment_remaining'] = round(max(0, $data['engagement'] - $data['payment_done']), 2);
        
        $student->update($data);
        
        // Redirect with preserved filters from session
        $filters = session('student_filters', []);
        return redirect()->route('students.index', $filters)
            ->with('success', 'Candidat modifié avec succès!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $studentName = $student->name;
        $student->delete();
        
        // Redirect with preserved filters from session
        $filters = session('student_filters', []);
        return redirect()->route('students.index', $filters)
            ->with('success', "Le candidat {$studentName} a été supprimé avec succès!");
    }

    /**
     * Import students from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        
        Excel::import(new StudentsImport, $request->file('file'));
        
        return redirect()->route('students.index')
            ->with('success', 'Données importées avec succès!');
    }



    public function export(Request $request)
{
    $query = Student::query()->with('formation');

    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('cin', 'LIKE', "%{$searchTerm}%")
              ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        });
    }

    if ($request->filled('formation_id')) {
        $query->where('formation_id', $request->formation_id);
    }

    if ($request->filled('city')) {
        $query->where('city', $request->city);
    }

    if ($request->filled('payment_status')) {
        if ($request->payment_status === 'paid') {
            $query->where('payment_remaining', '<=', 0);
        } elseif ($request->payment_status === 'unpaid') {
            $query->where('payment_remaining', '>', 0);
        }
    }

    if ($request->filled('date_from')) {
        $query->whereDate('start_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('start_date', '<=', $request->date_to);
    }

    return Excel::download(
        new StudentsExport($query),
        'candidats_' . date('Y-m-d') . '.xlsx'
    );
}

}