<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\Formation;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $r)
    {
        $q = Group::with('students','formation')->latest();
        if ($r->filled('date_from')) $q->whereDate('month_date','>=',$r->date_from);
        if ($r->filled('date_to'))   $q->whereDate('month_date','<=',$r->date_to);
        $groups = $q->paginate(15)->withQueryString();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $students = Student::with('formation')->orderBy('name')->get();
        $formations = Formation::orderBy('name')->get();
        return view('groups.create', compact('students','formations'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'         => 'required|string|max:255',
            'month_date'   => 'nullable|date',
            'formation_id' => 'nullable|exists:formations,id',
            'notes'        => 'nullable|string',
            'students'     => 'array',
            'students.*'   => 'exists:students,id',
        ]);

        $studentIds = $data['students'] ?? [];
        unset($data['students']); // remove before create() — not a column

        $group = Group::create($data);
        $group->students()->sync($studentIds);

        return redirect()->route('groups.index')->with('success', 'Groupe créé!');
    }

    public function show(Group $group)
    {
        $group->load('students.formation','formation');
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $students = Student::orderBy('name')->get();
        $formations = Formation::orderBy('name')->get();
        $selected = $group->students->pluck('id')->toArray();
        return view('groups.edit', compact('group','students','formations','selected'));
    }

    public function update(Request $r, Group $group)
    {
        $data = $r->validate([
            'name'         => 'required|string|max:255',
            'month_date'   => 'nullable|date',
            'formation_id' => 'nullable|exists:formations,id',
            'notes'        => 'nullable|string',
            'students'     => 'array',
            'students.*'   => 'exists:students,id',
        ]);

        $studentIds = $data['students'] ?? [];
        unset($data['students']); // remove before update() — not a column

        $group->update($data); // ← FIX: was Group::create($data) — wrong!
        $group->students()->sync($studentIds);

        return redirect()->route('groups.index')->with('success', 'Groupe modifié!');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('success', 'Groupe supprimé!');
    }
}