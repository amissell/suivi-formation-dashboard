@extends('layouts.app')

@section('title', 'Groupes')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-primary">Organisation pédagogique</p>
            <h2 class="mt-1 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">Groupes</h2>
            <p class="mt-1 text-sm text-muted-foreground">Gérez les groupes, les créneaux et les inscriptions.</p>
        </div>
        <a href="{{ route('groups.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:bg-primary/90">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nouveau groupe
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

{{-- FILTERS --}}
<div class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-5">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div class="flex flex-col">
            <label class="text-xs font-medium text-muted-foreground mb-1">Du</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex flex-col">
            <label class="text-xs font-medium text-muted-foreground mb-1">Au</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="flex flex-col">
            <label class="text-xs font-medium text-muted-foreground mb-1">Statut</label>
            <select name="status"
                    class="px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Tous</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Libéré</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
            </select>
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filtrer
        </button>
        <a href="{{ route('groups.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 hover:text-red-600 transition-colors">
            Réinitialiser
        </a>
    </form>
</div>

    {{-- TABLE --}}
    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-muted/50 text-muted-foreground">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Nom</th>
                        <th class="text-left font-medium px-4 py-3">Mois</th>
                        <th class="text-left font-medium px-4 py-3">Formation</th>
                        <th class="text-left font-medium px-4 py-3">📅 Semaine</th>
                        <th class="text-center font-medium px-4 py-3">Candidats</th>
                        <th class="text-right font-medium px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($groups as $g)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-foreground">{{ $g->name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ optional($g->month_date)->format('m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ optional($g->formation)->name ?? '—' }}</td>

                            {{-- SEMAINE COLUMN --}}
                            <td class="px-4 py-3">
                                @if(!empty($g->jours_semaine))
                                    @php $active = $g->isActive(); @endphp
                                    <div class="flex flex-col gap-0.5">
                                        <span class="inline-flex items-center gap-1.5 font-medium {{ $active ? 'text-foreground' : 'text-muted-foreground line-through' }}">
                                            {{ implode(', ', array_map(fn($j) => \App\Models\Group::JOURS[$j] ?? $j, $g->jours_semaine)) }}
                                            @if($g->slot_heure_debut)
                                                <span class="text-xs font-normal">
                                                    {{ \Carbon\Carbon::parse($g->slot_heure_debut)->format('H:i') }}
                                                    @if($g->slot_heure_fin)
                                                        –{{ \Carbon\Carbon::parse($g->slot_heure_fin)->format('H:i') }}
                                                    @endif
                                                </span>
                                            @endif
                                        </span>
                                        @if($active)
                                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">● Actif</span>
                                        @else
                                            <span class="text-xs text-muted-foreground">
                                                Libéré le {{ optional($g->date_fin)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400 font-medium">En attente</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $g->students->count() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('groups.show', $g) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded">Voir</a>
                                <a href="{{ route('groups.edit', $g) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded ml-1">Modifier</a>
                                <form action="{{ route('groups.destroy', $g) }}" method="POST"
                                      class="inline" onsubmit="return confirm('Supprimer ce groupe ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded ml-1">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-muted-foreground">
                                Aucun groupe trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $groups->links() }}
    </div>

</div>
@endsection
