<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class StudentController extends Controller
{
    /**
     * Display a listing of students with filters
     */
    public function index(Request $request)
    {
        // Start query with eager loading (prevents N+1 queries)
        $query = Student::with('formation');

        // ============================================
        // SEARCH FILTER - Multiple fields
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
        // STATUS FILTER
        // ============================================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ============================================
        // CITY FILTER - Exact match
        // ============================================
        if ($request->filled('city')) {
            // Changed from LIKE to exact match for dropdown
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
     * Export students to PDF with ALL filters applied
     */
    // public function exportPdf(Request $request)
    // {
    //     // Apply SAME filtering logic as index() method
    //     $query = Student::with('formation');

    //     // Search filter
    //     if ($request->filled('search')) {
    //         $searchTerm = $request->search;
    //         $query->where(function($q) use ($searchTerm) {
    //             $q->where('name', 'LIKE', "%{$searchTerm}%")
    //               ->orWhere('cin', 'LIKE', "%{$searchTerm}%")
    //               ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
    //               ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    //         });
    //     }

    //     // Formation filter
    //     if ($request->filled('formation_id')) {
    //         $query->where('formation_id', $request->formation_id);
    //     }

    //     // Status filter
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // City filter
    //     if ($request->filled('city')) {
    //         $query->where('city', $request->city);
    //     }

    //     // Payment status filter
    //     if ($request->filled('payment_status')) {
    //         if ($request->payment_status === 'paid') {
    //             $query->where('payment_remaining', '<=', 0);
    //         } elseif ($request->payment_status === 'unpaid') {
    //             $query->where('payment_remaining', '>', 0);
    //         }
    //     }

    //     // Date range filter
    //     if ($request->filled('date_from')) {
    //         $query->whereDate('start_date', '>=', $request->date_from);
    //     }
        
    //     if ($request->filled('date_to')) {
    //         $query->whereDate('start_date', '<=', $request->date_to);
    //     }

    //     // Get all filtered results (no pagination for PDF)
    //     $students = $query->latest()->get();

    //     // Generate PDF
    //     $pdf = PDF::loadView('students.pdf', compact('students'));

    //     // Download with timestamp in filename
    //     return $pdf->download('etudiants-' . date('Y-m-d-His') . '.pdf');
    // }

    // /**
    //  * Store a newly created student
    //  */


    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
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
            'payment_done' => 'required|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'status' => 'required|in:aide_vendeur,vendeur,superviseur,CDR',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        // Calculate remaining payment
        $data['payment_remaining'] = $data['engagement'] - $data['payment_done'];
        
        Student::create($data);
        
        return redirect()->route('students.index')->with('success', 'Étudiant ajouté avec succès!');
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
            'payment_done' => 'required|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'status' => 'required|in:aide_vendeur,vendeur,superviseur,CDR',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        // Recalculate remaining payment
        $data['payment_remaining'] = round(max(0, $data['engagement'] - $data['payment_done']), 2);
        
        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Étudiant modifié avec succès!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        $studentName = $student->name;
        $student->delete();
        
        return redirect()->route('students.index')->with('success', "L'étudiant {$studentName} a été supprimé avec succès!");
    }
}