@extends('layouts.app')

@section('title', 'Modifier le groupe')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold">Modifier : {{ $group->name }}</h2>

    <form method="POST" action="{{ route('groups.update', $group) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Nom</label>
            <input type="text" name="name" value="{{ old('name', $group->name) }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Mois</label>
            <input type="date" name="month_date"
                   value="{{ old('month_date', optional($group->month_date)->format('Y-m-d')) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Formation</label>
            <select name="formation_id" class="w-full border rounded px-3 py-2">
                <option value="">— Aucune —</option>
                @foreach($formations as $f)
                    <option value="{{ $f->id }}"
                        {{ (string)old('formation_id', $group->formation_id) === (string)$f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Notes</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes', $group->notes) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Candidats</label>
            <div class="max-h-64 overflow-y-auto border rounded p-3 space-y-1">
                @foreach($students as $s)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="students[]" value="{{ $s->id }}"
                               {{ in_array($s->id, old('students', $selected)) ? 'checked' : '' }}>
                        <span>{{ $s->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded">
                Enregistrer
            </button>
            <a href="{{ route('groups.index') }}" class="px-4 py-2 border rounded">Annuler</a>
        </div>
    </form>
</div>
@endsection
