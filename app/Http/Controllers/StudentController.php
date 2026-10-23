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

    public function create()
    {
        $formations = Formation::orderBy('name')->get();
        $professions = Student::PROFESSIONS;
        $student = new Student();
        $editing = false;

        return view('students.create', compact('formations', 'professions', 'student', 'editing'));
    }
  
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'formation_id', 'city', 'payment_status', 'profession', 'attestation', 'certificate_generated', 'date_from', 'date_to']);
        session(['student_filters' => array_filter($filters)]);

        $query = Student::with(['formation', 'groups.formation', 'attendances.seance']);
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

        // ============================================
        // PROFESSION (STATUS) FILTER
        // ============================================
        if ($request->filled('profession')) {
            $query->where('status', $request->profession);
        }

        if ($request->filled('attestation')) {
            $query->where('attestation', $request->attestation);
        }

        if ($request->filled('certificate_generated')) {
            $query->where('certificate_generated', $request->certificate_generated);
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

        // AJAX filter requests only need the results fragment re-rendered —
        // skip loading/computing the dropdown data and the full layout.
        if ($request->ajax()) {
            return view('students.partials.results', compact('students'));
        }

        // Get data for dropdowns
        $formations = Formation::orderBy('name')->get();
        $cities = Student::whereNotNull('city')
                        ->distinct()
                        ->pluck('city')
                        ->filter()
                        ->sort()
                        ->values();
        $professions = Student::PROFESSIONS;

        return view('students.index', compact('students', 'formations', 'cities', 'professions'));
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
            'payment_done' => 'nullable|numeric|min:0',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $data['attestation'] = 'no';
        $data['certificate_generated'] = 'no';
        $data['status'] = 'aide_vendeur';

        // Calculate remaining payment
        $data['payment_done'] = $data['payment_done'] ?? 0;
        $data['payment_remaining'] = $data['engagement'] - $data['payment_done'];

        Student::create($data);

        // Redirect with preserved filters from session
        $filters = session('student_filters', []);
        return redirect()->route('students.index', $filters)
            ->with('success', 'Candidat ajouté avec succès!');
    }

    public function edit(Student $student)
    {
        $formations = Formation::orderBy('name')->get();
        $professions = Student::PROFESSIONS;
        $editing = true;

        return view('students.edit', compact('formations', 'professions', 'student', 'editing'));
    }

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
            'certificate_generated' => 'required|in:yes,no',
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
    public function destroy(Student $student)
    {
        $studentName = $student->name;
        $student->delete();

        // Redirect with preserved filters from session
        $filters = session('student_filters', []);
        return redirect()->route('students.index', $filters)
            ->with('success', "Le candidat {$studentName} a été supprimé avec succès!");
    }

    public function filter(Request $request)
    {
        return $this->index($request);
    }


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
