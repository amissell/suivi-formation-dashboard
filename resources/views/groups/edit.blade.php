@extends('layouts.app')

@section('title', 'Modifier le groupe')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Modifier le groupe</h2>
            <p class="text-muted-foreground">{{ $group->name }}</p>
        </div>
        <a href="{{ route('groups.index') }}"
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

    <form method="POST" action="{{ route('groups.update', $group) }}"
          class="bg-card border border-border rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Nom --}}
        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Nom *</label>
            <input type="text" name="name" value="{{ old('name', $group->name) }}" required
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Mois</label>
                <input type="date" name="month_date"
                       value="{{ old('month_date', optional($group->month_date)->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Formation</label>
                <select name="formation_id"
                        class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">— Aucune —</option>
                    @foreach($formations as $f)
                        <option value="{{ $f->id }}"
                            {{ (string)old('formation_id', $group->formation_id) === (string)$f->id ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ═══════════════ PLANNING SECTION ═══════════════ --}}
        <div class="border border-border rounded-xl p-4 bg-muted/20 space-y-4">
            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Créneau hebdomadaire
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-muted-foreground mb-1">Jours de la semaine (max 2)</label>
                    <div id="jours_semaine" class="flex flex-wrap gap-2">
                        @foreach(\App\Models\Group::JOURS as $val => $label)
                            <label class="jour-chip inline-flex items-center gap-1.5 px-2.5 py-1.5 border border-border rounded-lg cursor-pointer text-sm hover:bg-muted transition-colors">
                                <input type="checkbox" name="jours_semaine[]" value="{{ $val }}" class="jour-checkbox rounded border-border"
                                       {{ in_array($val, old('jours_semaine', $group->jours_semaine ?? [])) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted-foreground mb-1">Heure début</label>
                    <input type="time" name="slot_heure_debut" id="slot_heure_debut"
                           value="{{ old('slot_heure_debut', $group->slot_heure_debut ? \Carbon\Carbon::parse($group->slot_heure_debut)->format('H:i') : '') }}"
                           class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-muted-foreground mb-1">Heure fin</label>
                    <input type="time" name="slot_heure_fin" id="slot_heure_fin"
                           value="{{ old('slot_heure_fin', $group->slot_heure_fin ? \Carbon\Carbon::parse($group->slot_heure_fin)->format('H:i') : '') }}"
                           class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-muted-foreground mb-1">Date de fin de formation</label>
                    <input type="date" name="date_fin" id="date_fin"
                           value="{{ old('date_fin', optional($group->date_fin)->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-muted-foreground mt-1">Après cette date, le créneau sera libéré</p>
                </div>
            </div>

            {{-- CONFLICT ALERT --}}
            <div id="conflict-box" class="hidden rounded-lg border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Conflit de créneau !</p>
                        <ul id="conflict-list" class="mt-1 text-xs text-amber-700 dark:text-amber-300 space-y-1 list-disc ml-4"></ul>
                    </div>
                </div>
            </div>

            <div id="free-slot-box" class="hidden rounded-lg border border-green-300 bg-green-50 dark:bg-green-900/20 dark:border-green-700 p-3">
                <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Ce créneau est disponible ✓
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $group->notes) }}</textarea>
        </div>

        {{-- STUDENT SELECTOR --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-foreground">Candidats</label>
                <div class="flex gap-2">
                    <button type="button" onclick="toggleAll(true)"
                            class="px-2.5 py-1 text-xs border border-border rounded hover:bg-muted transition-colors">Tout cocher</button>
                    <button type="button" onclick="toggleAll(false)"
                            class="px-2.5 py-1 text-xs border border-border rounded hover:bg-muted transition-colors">Tout décocher</button>
                </div>
            </div>
            <input type="text" id="studentSearch" placeholder="🔍 Rechercher un candidat..."
                   onkeyup="filterStudents()"
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground mb-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <div id="studentsList"
                 class="max-h-72 overflow-y-auto border border-border rounded-lg p-3 space-y-1 bg-background">
                @foreach($students as $s)
                    <label class="student-item flex items-center gap-2 hover:bg-muted/50 p-1.5 rounded cursor-pointer">
                        <input type="checkbox" name="students[]" value="{{ $s->id }}"
                               {{ in_array($s->id, old('students', $selected)) ? 'checked' : '' }}
                               class="rounded border-border">
                        <span class="student-name text-sm text-foreground">
                            {{ $s->name }}
                            @if($s->formation)
                                <span class="text-xs text-muted-foreground">({{ $s->formation->name }})</span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-muted-foreground mt-1.5">
                <span id="selectedCount">0</span> candidat(s) sélectionné(s)
            </p>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                Enregistrer
            </button>
            <a href="{{ route('groups.index') }}"
               class="px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors font-medium">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleAll(checked) {
    document.querySelectorAll('#studentsList input[type=checkbox]').forEach(cb => {
        if (cb.closest('.student-item').style.display !== 'none') cb.checked = checked;
    });
    updateCount();
}
function filterStudents() {
    const q = document.getElementById('studentSearch').value.toLowerCase();
    document.querySelectorAll('.student-item').forEach(item => {
        const name = item.querySelector('.student-name').textContent.toLowerCase();
        item.style.display = name.includes(q) ? '' : 'none';
    });
}
function updateCount() {
    const n = document.querySelectorAll('#studentsList input[type=checkbox]:checked').length;
    document.getElementById('selectedCount').textContent = n;
}
document.addEventListener('change', e => {
    if (e.target.matches('#studentsList input')) updateCount();
});
updateCount();

// ─── Conflict checker ───────────────────────────────────────────────────────
const jourChecks = document.querySelectorAll('.jour-checkbox');
const debutEl = document.getElementById('slot_heure_debut');
const finEl   = document.getElementById('slot_heure_fin');
const conflictBox  = document.getElementById('conflict-box');
const conflictList = document.getElementById('conflict-list');
const freeBox      = document.getElementById('free-slot-box');
const currentId    = {{ $group->id }};

jourChecks.forEach(cb => cb.addEventListener('change', () => {
    const checked = document.querySelectorAll('.jour-checkbox:checked');
    if (checked.length > 2) {
        cb.checked = false;
        return;
    }
    checkConflicts();
}));

let debounceTimer = null;

function checkConflicts() {
    const jours = Array.from(document.querySelectorAll('.jour-checkbox:checked')).map(c => c.value);
    const debut = debutEl.value;
    const fin   = finEl.value;

    conflictBox.classList.add('hidden');
    freeBox.classList.add('hidden');

    if (jours.length === 0 || !debut) return;

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(async () => {
        const params = new URLSearchParams({ exclude: currentId });
        jours.forEach(j => params.append('jours[]', j));
        params.append('debut', debut);
        if (fin) params.append('fin', fin);

        const res  = await fetch(`{{ route('groups.checkSlot') }}?${params}`);
        const data = await res.json();

        if (data.conflicts.length > 0) {
            conflictList.innerHTML = data.conflicts.map(c => {
                let text = `<strong>${c.name}</strong> — ${c.slot}`;
                if (c.formation) text += ` (${c.formation})`;
                if (c.date_fin)  text += ` · libéré le ${c.date_fin}`;
                return `<li>${text}</li>`;
            }).join('');
            conflictBox.classList.remove('hidden');
        } else {
            freeBox.classList.remove('hidden');
        }
    }, 400);
}

[debutEl, finEl].forEach(el => el.addEventListener('change', checkConflicts));

// Run on page load if values already set
if (document.querySelectorAll('.jour-checkbox:checked').length > 0 && debutEl.value) checkConflicts();
</script>
@endpush
@endsection