<?php

namespace App\Http\Controllers;
use App\Models\Formation;

use Illuminate\Http\Request;

class formationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = Formation::all();
        return view('formations.index', compact('formations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('formations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'trainer' => 'required|string|max:255',
    ]);
    Formation::create($validated);
    return redirect()->route('formations.index')->with('success', 'Formation added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation) {
        $formation->update($request->all());
        return redirect()->route('formations.index');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation) {
        $formation->delete();
        return redirect()->route('formations.index');
}

}
