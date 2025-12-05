<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('formation')->latest()->get();
        $formations = Formation::all();
        return view('students.index', compact('students', 'formations'));
    }

    public function store(Request $request)
    {
        // dd(vars: $request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cin' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'formation_id' => 'required|exists:formations,id',
            'start_date' => 'required|date',
            'payment_done' => 'required|numeric',
            // 'payment_remaining' => 'required|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'status' => 'required|in:aide_vendeur,vendeur,superviseur,CDR',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        
         $formation = Formation::findOrFail($data['formation_id']);
         
        //  $paymentDone = $request->payment_done ?? 0;
        //  $paymentRemaining = $formation->price - $paymentDone;
         
        //  $data['payment_remaining'] = $paymentRemaining;
        $data['payment_remaining'] = $formation->price - $data['payment_done'];
         
         $data['attestation'] = 'no';

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
            'payment_done' => 'required|numeric|min:0',
            'payment_remaining' => 'required|numeric|min:0',
            'attestation' => 'required|in:yes,no',
            'status' => 'required|in:aide_vendeur,vendeur,superviseur,CDR',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        
        $formation = Formation::find($data['formation_id']);
        $price = $formation ? (float)$formation->price : 0;
        $paid = isset($data['payment_done']) ? (float)$data['payment_done'] : 0;
        
        $data['payment_remaining'] = round(max(0, $price - $paid), 2);

        $student->update($data);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully!');
    }
}
