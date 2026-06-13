<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Seance;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    public function index(Group $group)
    {
        $seances = $group->seances()->paginate(20);
        return view('seances.index', compact('group', 'seances'));
    }

    public function create(Group $group)
    {
        return view('seances.create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'date_seance' => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin'   => 'nullable|date_format:H:i|after:heure_debut',
            'type'        => 'nullable|string|max:100',
            'lien_zoom'   => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'statut'      => 'nullable|in:planifiée,réalisée,annulée',
        ]);

        $data['group_id'] = $group->id;
        Seance::create($data);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Séance ajoutée avec succès!');
    }

    public function edit(Group $group, Seance $seance)
    {
        return view('seances.edit', compact('group', 'seance'));
    }

    public function update(Request $request, Group $group, Seance $seance)
    {
        $data = $request->validate([
            'titre'       => 'required|string|max:255',
            'date_seance' => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin'   => 'nullable|date_format:H:i|after:heure_debut',
            'type'        => 'nullable|string|max:100',
            'lien_zoom'   => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'statut'      => 'nullable|in:planifiée,réalisée,annulée',
        ]);

        $seance->update($data);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Séance modifiée avec succès!');
    }

    public function destroy(Group $group, Seance $seance)
    {
        $seance->delete();

        return back()->with('success', 'Séance supprimée!');
    }
}