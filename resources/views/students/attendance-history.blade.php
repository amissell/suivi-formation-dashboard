@extends('layouts.app')

@section('title', 'Historique des présences')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-foreground">{{ $student->name }}</h2>
            <p class="text-muted-foreground">{{ $group->name }} · Historique des présences</p>
        </div>
        <a href="{{ route('groups.show', $group) }}" class="px-4 py-2 border border-border rounded-lg hover:bg-muted text-sm">← Retour au groupe</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach(['present' => 'Présent', 'absent' => 'Absent', 'late' => 'En retard', 'recorded' => 'Séances enregistrées'] as $key => $label)
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm text-muted-foreground">{{ $label }}</p>
                <p class="text-3xl font-bold text-foreground mt-1">{{ $totals[$key] }}</p>
            </div>
        @endforeach
    </div>
    <p class="text-sm text-muted-foreground">{{ $totals['recorded'] }} présence(s) enregistrée(s) sur {{ $totals['sessions'] }} séance(s) non annulée(s) du groupe.</p>

    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-border"><h3 class="font-semibold text-foreground">Détail par séance</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-muted/50 text-muted-foreground">
                    <tr><th class="text-left px-4 py-3">Date</th><th class="text-left px-4 py-3">Séance</th><th class="text-left px-4 py-3">Statut</th></tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($history as $attendance)
                        <tr>
                            <td class="px-4 py-3">{{ optional($attendance->seance->date_seance)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $attendance->seance->titre }}</td>
                            <td class="px-4 py-3">{{ \App\Models\Attendance::STATUSES[$attendance->status] ?? $attendance->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-10 text-muted-foreground">Aucun historique enregistré pour cet étudiant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
