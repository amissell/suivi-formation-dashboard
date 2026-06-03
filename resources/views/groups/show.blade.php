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

    {{-- INFO CARDS --}}
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($group->students as $s)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-foreground">{{ $s->name }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $s->cin ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $s->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ optional($s->formation)->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-muted-foreground">
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