@extends('layouts.app')

@section('title', $group->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-foreground">{{ $group->name }}</h2>
            <p class="text-muted-foreground">
                Mois : {{ optional($group->month_date)->format('m/Y') ?? '—' }}
                @if($group->formation)
                    · Formation : {{ $group->formation->name }}
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('groups.edit', $group) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors text-sm font-medium">
                Modifier
            </a>
            <a href="{{ route('groups.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors text-sm font-medium">
                ← Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-medium text-muted-foreground mb-2">Candidats</h3>
            <div class="text-3xl font-bold text-foreground">{{ $group->students->count() }}</div>
        </div>
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-medium text-muted-foreground mb-2">Mois</h3>
            <div class="text-3xl font-bold text-foreground">
                {{ optional($group->month_date)->format('m/Y') ?? '—' }}
            </div>
        </div>
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-sm font-medium text-muted-foreground mb-2">Formation</h3>
            <div class="text-lg font-semibold text-foreground">
                {{ optional($group->formation)->name ?? '—' }}
            </div>
        </div>
    </div>

    {{-- NOTES --}}
    @if($group->notes)
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-2">Notes</h3>
            <p class="text-muted-foreground whitespace-pre-line">{{ $group->notes }}</p>
        </div>
    @endif

    {{-- SEANCES --}}
    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-border flex items-center justify-between flex-wrap gap-2">
            <h3 class="text-lg font-semibold text-foreground">
                Séances ({{ $group->seances->count() }})
            </h3>
            <a href="{{ route('groups.seances.create', $group) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                + Nouvelle séance
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-muted/50 text-muted-foreground">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Titre</th>
                        <th class="text-left font-medium px-4 py-3">Date</th>
                        <th class="text-left font-medium px-4 py-3">Horaire</th>
                        <th class="text-left font-medium px-4 py-3">Statut</th>
                        <th class="text-right font-medium px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($group->seances as $seance)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-foreground">{{ $seance->titre }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ optional($seance->date_seance)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-muted-foreground">
                                @if($seance->heure_debut)
                                    {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}
                                    @if($seance->heure_fin)–{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}@endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusBadge = [
                                        'planifiée' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                        'réalisée'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                        'annulée'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    ][$seance->statut] ?? '';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                                    {{ ucfirst($seance->statut) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('seances.attendance.edit', [$group, $seance]) }}"
                                       class="px-2.5 py-1 text-xs border border-border rounded hover:bg-muted transition-colors">
                                        Présences
                                    </a>
                                    <a href="{{ route('groups.seances.edit', [$group, $seance]) }}"
                                       class="px-2.5 py-1 text-xs border border-border rounded hover:bg-muted transition-colors">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('groups.seances.destroy', [$group, $seance]) }}"
                                          onsubmit="return confirm('Supprimer cette séance ?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 text-xs border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-muted-foreground">
                                Aucune séance pour ce groupe.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STUDENTS TABLE --}}
    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">
                Candidats ({{ $group->students->count() }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-muted/50 text-muted-foreground">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Nom</th>
                        <th class="text-left font-medium px-4 py-3">CIN</th>
                        <th class="text-left font-medium px-4 py-3">Téléphone</th>
                        <th class="text-left font-medium px-4 py-3">Formation</th>
                        <th class="text-left font-medium px-4 py-3">Engagement</th>
                        <th class="text-right font-medium px-4 py-3">Historique</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($group->students as $s)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-foreground">{{ $s->name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $s->cin ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $s->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ optional($s->formation)->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php $rate = $s->engagement_rate; @endphp
                                @if($rate === null)
                                    <span class="text-xs text-muted-foreground">Aucune séance réalisée</span>
                                @else
                                    @php
                                        $barColor = $rate >= 75 ? 'bg-green-500' : ($rate >= 50 ? 'bg-amber-500' : 'bg-red-500');
                                    @endphp
                                    <div class="flex items-center gap-2 min-w-[140px]">
                                        <div class="flex-1 bg-muted h-1.5 rounded-full overflow-hidden">
                                            <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $rate }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-foreground">{{ $rate }}%</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('groups.students.attendance', [$group, $s]) }}" class="px-2.5 py-1 text-xs border border-border rounded hover:bg-muted transition-colors">Voir les présences</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-muted-foreground">
                                Aucun candidat dans ce groupe
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
