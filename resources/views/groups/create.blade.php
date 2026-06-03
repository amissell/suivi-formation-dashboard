@extends('layouts.app')

@section('title', 'Nouveau groupe')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Nouveau groupe</h2>
            <p class="text-muted-foreground">Créer un nouveau groupe de formation</p>
        </div>
        <a href="{{ route('groups.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-muted transition-colors text-sm font-medium">
            ← Retour
        </a>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="p-4 rounded-lg border bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800">
            <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('groups.store') }}"
          class="bg-card border border-border rounded-xl shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Nom du groupe *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Ex: Groupe Janvier 2026"
                   class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Mois</label>
                <input type="date" name="month_date" value="{{ old('month_date') }}"
                       class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-foreground mb-1.5">Formation</label>
                <select name="formation_id"
                        class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">— Aucune —</option>
                    @foreach($formations as $f)
                        <option value="{{ $f->id }}" {{ (string)old('formation_id') === (string)$f->id ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-foreground mb-1.5">Notes</label>
            <textarea name="notes" rows="3"
                      class="w-full px-3 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
        </div>

        {{-- STUDENT SELECTOR --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-foreground">Sélectionner les candidats</label>
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
                @forelse($students as $s)
                    <label class="student-item flex items-center gap-2 hover:bg-muted/50 p-1.5 rounded cursor-pointer">
                        <input type="checkbox" name="students[]" value="{{ $s->id }}"
                               {{ in_array($s->id, old('students', [])) ? 'checked' : '' }}
                               class="rounded border-border">
                        <span class="student-name text-sm text-foreground">
                            {{ $s->name }}
                            @if($s->formation)
                                <span class="text-xs text-muted-foreground">({{ $s->formation->name }})</span>
                            @endif
                        </span>
                    </label>
                @empty
                    <p class="text-muted-foreground text-sm">Aucun candidat disponible.</p>
                @endforelse
            </div>
            <p class="text-xs text-muted-foreground mt-1.5">
                <span id="selectedCount">0</span> candidat(s) sélectionné(s)
            </p>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                Créer le groupe
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
</script>
@endpush
@endsection