@extends('layouts.app')

@section('title', 'Nouvelle formation')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <a href="{{ route('formations.index') }}" class="text-sm font-medium text-muted-foreground transition hover:text-primary">← Retour aux formations</a>
        <h2 class="mt-3 text-2xl font-bold text-foreground sm:text-3xl">Nouvelle formation</h2>
        <p class="mt-1 text-sm text-muted-foreground">Ajoutez une formation au catalogue pédagogique.</p>
    </div>
    @if($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
            <p class="font-semibold">Vérifiez les informations saisies :</p>
            <ul class="mt-2 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <form action="{{ route('formations.store') }}" method="POST" class="space-y-5 rounded-xl border border-border bg-card p-5 shadow-sm sm:p-7">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-foreground">Nom de la formation <span class="text-red-500">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Ex. Vente et relation client" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="trainer" class="mb-1.5 block text-sm font-medium text-foreground">Formateur <span class="text-red-500">*</span></label>
            <input id="trainer" type="text" name="trainer" value="{{ old('trainer') }}" required maxlength="255" placeholder="Nom du formateur" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('trainer')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col-reverse gap-2 border-t border-border pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('formations.index') }}" class="rounded-lg border border-border px-4 py-2.5 text-center text-sm font-semibold text-foreground hover:bg-muted">Annuler</a>
            <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90">Créer la formation</button>
        </div>
    </form>
</div>
@endsection
