@extends('layouts.app')

@section('title', 'Nouvelle séance')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-primary">{{ $group->name }}</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">Nouvelle séance</h2>
            <p class="mt-1 text-sm text-muted-foreground">Planifiez une séance et définissez son horaire.</p>
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

    <form method="POST" action="{{ route('groups.seances.store', $group) }}"
          class="bg-card border border-border rounded-xl shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre') }}" required
                   placeholder="Ex: Séance 1 - Introduction"
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Date *</label>
                <input type="date" name="date_seance" value="{{ old('date_seance') }}" required
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Heure début</label>
                <input type="time" name="heure_debut" value="{{ old('heure_debut') }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Heure fin</label>
                <input type="time" name="heure_fin" value="{{ old('heure_fin') }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Type</label>
                <input type="text" name="type" value="{{ old('type') }}" placeholder="Ex: Présentiel, Visio..."
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Statut</label>
                <select name="statut"
                        class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="planifiée" {{ old('statut') === 'planifiée' ? 'selected' : '' }}>Planifiée</option>
                    <option value="réalisée" {{ old('statut') === 'réalisée' ? 'selected' : '' }}>Réalisée</option>
                    <option value="annulée" {{ old('statut') === 'annulée' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Lien Zoom / visio</label>
            <input type="url" name="lien_zoom" value="{{ old('lien_zoom') }}" placeholder="https://..."
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Description</label>
            <textarea name="description" rows="3"
                      class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description') }}</textarea>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-border pt-5 sm:flex-row sm:justify-end">
            <button type="submit"
                    class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:bg-primary/90">
                Créer la séance
            </button>
            <a href="{{ route('groups.show', $group) }}"
               class="rounded-lg border border-border px-4 py-2.5 text-center text-sm font-semibold text-foreground transition hover:bg-muted">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
