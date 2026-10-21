@extends('layouts.app')

@section('title', 'Présences')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Présences</h2>
            <p class="text-muted-foreground">
                {{ $group->name }} · {{ $seance->titre }} ·
                {{ optional($seance->date_seance)->format('d/m/Y') }}
                @if($seance->heure_debut)
                    · {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}
                    @if($seance->heure_fin)–{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}@endif
                @endif
            </p>
        </div>
        <a href="{{ route('groups.show', $group) }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors text-sm font-medium">
            ← Retour
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg border bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if($seance->statut !== 'réalisée')
        <div class="p-4 rounded-lg border bg-amber-50 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800 text-sm text-amber-700 dark:text-amber-300">
            Cette séance est marquée comme <strong>{{ $seance->statut }}</strong>. Les présences ne compteront dans le taux d'engagement que si le statut de la séance est "réalisée".
        </div>
    @endif

    <form method="POST" action="{{ route('seances.attendance.update', [$group, $seance]) }}"
          class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">
        @csrf

        <div class="p-4 border-b border-border flex items-center justify-between flex-wrap gap-2">
            <h3 class="text-sm font-semibold text-foreground">{{ $students->count() }} candidat(s)</h3>
            <div class="flex gap-2">
                <button type="button" onclick="markAll('present')"
                        class="px-2.5 py-1 text-xs border border-green-300 text-green-700 dark:text-green-300 dark:border-green-700 rounded hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                    Tous présents
                </button>
                <button type="button" onclick="markAll('absent')"
                        class="px-2.5 py-1 text-xs border border-red-300 text-red-700 dark:text-red-300 dark:border-red-700 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    Tous absents
                </button>
            </div>
        </div>

        <div class="divide-y divide-border">
            @forelse($students as $s)
                @php $current = $existing[$s->id]->status ?? null; @endphp
                <div class="flex items-center justify-between gap-4 p-4 attendance-row" data-student="{{ $s->id }}">
                    <div class="min-w-0">
                        <p class="font-medium text-sm text-foreground truncate">{{ $s->name }}</p>
                        <p class="text-xs text-muted-foreground">{{ $s->cin }}</p>
                    </div>
                    <div class="flex gap-1.5 shrink-0">
                        @php
                            $statusStyles = [
                                'present' => 'border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 peer-checked:bg-green-100 peer-checked:dark:bg-green-900/40 peer-checked:ring-2 peer-checked:ring-green-400 hover:bg-green-50 dark:hover:bg-green-900/20',
                                'absent'  => 'border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 peer-checked:bg-red-100 peer-checked:dark:bg-red-900/40 peer-checked:ring-2 peer-checked:ring-red-400 hover:bg-red-50 dark:hover:bg-red-900/20',
                                'late'    => 'border-amber-300 dark:border-amber-700 text-amber-700 dark:text-amber-300 peer-checked:bg-amber-100 peer-checked:dark:bg-amber-900/40 peer-checked:ring-2 peer-checked:ring-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20',
                            ];
                        @endphp
                        @foreach(\App\Models\Attendance::STATUSES as $val => $label)
                            <label class="status-chip cursor-pointer">
                                <input type="radio" name="statuses[{{ $s->id }}]" value="{{ $val }}"
                                       {{ ($current === $val) || (!$current && $val === 'present') ? 'checked' : '' }}
                                       class="sr-only peer">
                                <span class="px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors block {{ $statusStyles[$val] }}">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <span class="attendance-save-state text-xs text-muted-foreground w-16 text-right" aria-live="polite"></span>
                </div>
            @empty
                <div class="text-center py-10 text-muted-foreground">
                    Aucun candidat dans ce groupe.
                </div>
            @endforelse
        </div>

        @if($students->count() > 0)
        <div class="p-4 border-t border-border">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                Enregistrer les présences
            </button>
        </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.attendance-row').forEach(row => {
        const input = row.querySelector(`input[value="${status}"]`);
        if (input) input.checked = true;
    });
}

// Save each individual choice immediately. The form still supports bulk saves
// after using “Tous présents/absents”.
document.querySelectorAll('.attendance-row input[type="radio"]').forEach(input => {
    input.addEventListener('change', async () => {
        const row = input.closest('.attendance-row');
        const state = row.querySelector('.attendance-save-state');
        state.textContent = 'Saving…';

        try {
            const body = new URLSearchParams();
            body.append(`statuses[${row.dataset.student}]`, input.value);
            const response = await fetch(@json(route('seances.attendance.update', [$group, $seance])), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'Accept': 'application/json',
                },
                body,
            });
            if (!response.ok) throw new Error('Save failed');
            state.textContent = 'Saved';
        } catch (error) {
            state.textContent = 'Not saved';
        }
    });
});
</script>
@endpush
@endsection
