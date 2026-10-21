@extends('layouts.app')

@section('title', 'Formations')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-primary">Catalogue pédagogique</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">Formations</h2>
            <p class="mt-1 text-sm text-muted-foreground">Gérez les formations et leurs formateurs.</p>
        </div>
        <button type="button" onclick="openFormationModal('add')" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
            Ajouter une formation
        </button>
    </div>

    @if(session('success'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    @if($formations->isEmpty())
        <div class="rounded-2xl border border-dashed border-border bg-card px-6 py-16 text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-primary dark:bg-blue-400/10">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="mt-4 font-semibold text-foreground">Aucune formation pour le moment</h3>
            <p class="mt-1 text-sm text-muted-foreground">Ajoutez votre première formation pour commencer.</p>
            <button type="button" onclick="openFormationModal('add')" class="mt-5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground hover:bg-primary/90">Créer une formation</button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($formations as $formation)
                <article class="group flex min-h-48 flex-col justify-between rounded-xl border border-border bg-card p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                    <div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-primary dark:bg-blue-400/10">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-lg font-semibold text-foreground">{{ $formation->name }}</h3>
                                <p class="mt-1 text-sm text-muted-foreground">Formateur : <span class="font-medium text-foreground">{{ $formation->trainer }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-2 border-t border-border pt-4">
                        <button type="button" onclick='openFormationModal("edit", @json($formation))' class="inline-flex items-center gap-1.5 rounded-lg border border-border px-3 py-2 text-xs font-semibold text-foreground transition hover:bg-muted" aria-label="Modifier {{ $formation->name }}">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m16 4 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Modifier
                        </button>
                        <form action="{{ route('formations.destroy', $formation) }}" method="POST" onsubmit="return confirm('Supprimer cette formation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900/60 dark:text-red-300 dark:hover:bg-red-950/30" aria-label="Supprimer {{ $formation->name }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@include('formations.modal')
@endsection
