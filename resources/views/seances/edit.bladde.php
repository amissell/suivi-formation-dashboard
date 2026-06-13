@extends('layouts.app')

@section('title', 'Modifier la séance')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Modifier la séance</h2>
            <p class="text-muted-foreground">{{ $group->name }}</p>
        </div>
        <a href="{{ route('groups.show', $group) }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors text-sm font-medium">
            ← Retour
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-lg border bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800">
            <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('groups.seances.update', [$group, $seance]) }}"
          class="bg-card border border-border rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre', $seance->titre) }}" required
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Date *</label>
                <input type="date" name="date_seance" value="{{ old('date_seance', optional($seance->date_seance)->format('Y-m-d')) }}" required
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Heure début</label>
                <input type="time" name="heure_debut"
                       value="{{ old('heure_debut', $seance->heure_debut ? \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') : '') }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Heure fin</label>
                <input type="time" name="heure_fin"
                       value="{{ old('heure_fin', $seance->heure_fin ? \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') : '') }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Type</label>
                <input type="text" name="type" value="{{ old('type', $seance->type) }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Statut</label>
                <select name="statut"
                        class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach(['planifiée' => 'Planifiée', 'réalisée' => 'Réalisée', 'annulée' => 'Annulée'] as $val => $label)
                        <option value="{{ $val }}" {{ old('statut', $seance->statut) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Lien Zoom / visio</label>
            <input type="url" name="lien_zoom" value="{{ old('lien_zoom', $seance->lien_zoom) }}"
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Description</label>
            <textarea name="description" rows="3"
                      class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $seance->description) }}</textarea>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                Enregistrer
            </button>
            <a href="{{ route('groups.show', $group) }}"
               class="px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors font-medium">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection