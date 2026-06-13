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
        $students   = Student::with('formation')->orderBy('name')->get();
        $formations = Formation::orderBy('name')->get();
        // Pass all active slots for the conflict warning UI
        $activeSlots = Group::whereNotNull('jours_semaine')
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhereDate('date_fin', '>=', now()->toDateString());
            })
            ->get(['id','name','jours_semaine','slot_heure_debut','slot_heure_fin','date_fin']);
        return view('groups.create', compact('students','formations','activeSlots'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'             => 'required|string|max:255',
            'month_date'       => 'nullable|date',
            'formation_id'     => 'nullable|exists:formations,id',
            'notes'            => 'nullable|string',
            'students'         => 'array',
            'students.*'       => 'exists:students,id',
            'jours_semaine'    => 'nullable|array|max:2',
            'jours_semaine.*'  => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi',
            'slot_heure_debut' => 'nullable|date_format:H:i',
            'slot_heure_fin'   => 'nullable|date_format:H:i|after:slot_heure_debut',
            'date_fin'         => 'nullable|date',
        ]);

        $studentIds = $data['students'] ?? [];
        unset($data['students']);

        $data['jours_semaine'] = !empty($data['jours_semaine']) ? array_values($data['jours_semaine']) : null;

        $group = Group::create($data);
        $group->students()->sync($studentIds);

        return redirect()->route('groups.index')->with('success', 'Groupe créé!');
    }

    public function show(Group $group)
    {
        $group->load('students.formation', 'students.attendances.seance', 'formation', 'seances');
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $students   = Student::orderBy('name')->get();
        $formations = Formation::orderBy('name')->get();
        $selected   = $group->students->pluck('id')->toArray();
        $activeSlots = Group::whereNotNull('jours_semaine')
            ->where('id', '!=', $group->id)
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhereDate('date_fin', '>=', now()->toDateString());
            })
            ->get(['id','name','jours_semaine','slot_heure_debut','slot_heure_fin','date_fin']);
        return view('groups.edit', compact('group','students','formations','selected','activeSlots'));
    }

    public function update(Request $r, Group $group)
    {
        $data = $r->validate([
            'name'             => 'required|string|max:255',
            'month_date'       => 'nullable|date',
            'formation_id'     => 'nullable|exists:formations,id',
            'notes'            => 'nullable|string',
            'students'         => 'array',
            'students.*'       => 'exists:students,id',
            'jours_semaine'    => 'nullable|array|max:2',
            'jours_semaine.*'  => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi',
            'slot_heure_debut' => 'nullable|date_format:H:i',
            'slot_heure_fin'   => 'nullable|date_format:H:i|after:slot_heure_debut',
            'date_fin'         => 'nullable|date',
        ]);

        $studentIds = $data['students'] ?? [];
        unset($data['students']);

        $data['jours_semaine'] = !empty($data['jours_semaine']) ? array_values($data['jours_semaine']) : null;

        $group->update($data);
        $group->students()->sync($studentIds);

        return redirect()->route('groups.index')->with('success', 'Groupe modifié!');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('success', 'Groupe supprimé!');
    }

    /**
     * AJAX: check conflicts for given slot
     * GET /groups/check-slot?jours[]=lundi&jours[]=mercredi&debut=09:00&fin=11:00&exclude=5
     */
    public function checkSlot(Request $r)
    {
        $jours = (array) $r->input('jours', []);
        $debut = $r->input('debut');
        $fin   = $r->input('fin');
        $excl  = $r->input('exclude') ? (int)$r->input('exclude') : null;

        if (empty($jours) || !$debut) {
            return response()->json(['conflicts' => []]);
        }

        $conflicts = Group::conflicts($jours, $debut, $fin ?: null, $excl);

        return response()->json([
            'conflicts' => $conflicts->map(fn($g) => [
                'id'       => $g->id,
                'name'     => $g->name,
                'slot'     => $g->slotLabel(),
                'date_fin' => $g->date_fin?->format('d/m/Y'),
                'formation'=> $g->formation?->name,
            ])
        ]);
    }
}