<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class StudentController extends Controller
{
    public function index(Request $request)
    {
        $formations = Formation::all();
        $query = Student::with('formation');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Pagination
        $students = $query->latest()->paginate(20)->withQueryString();
        
        $students = Student::with('formation')
    ->when($request->formation_id, function ($query, $formationId) {
        return $query->where('formation_id', $formationId);
    })
    ->when($request->search, function($query, $search) {
        return $query->where('name', 'like', "%{$search}%");
    })
    ->latest()
    ->paginate(10)
    ->withQueryString();

        
        return view('students.index', compact('students', 'formations'));
}


public function exportPdf(Request $request)
    {
        // Build same query for PDF export
        $query = Student::with('formation');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $students = $query->latest()->get();

        // Generate PDF
        $pdf = PDF::loadView('students.pdf', compact('students'));

        return $pdf->download('students.pdf');
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
        
        $data['payment_remaining'] = $data['engagement'] - $data['payment_done'];
        
        Student::create($data);
        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function edit(Student $student)
    {
        $formations = Formation::all();
        return view('students.edit', compact('student', 'formations'));
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
            'payment_done' => 'required|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'status' => 'required|in:aide_vendeur,vendeur,superviseur,CDR',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        $data['payment_remaining'] = round(max(0, $data['engagement'] - $data['payment_done']), 2);
        $student->update($data);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully!');
    }
}
