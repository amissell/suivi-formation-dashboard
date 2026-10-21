@extends('layouts.app')

@section('title', 'Nouveau candidat')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <a href="{{ route('students.index') }}" class="text-sm font-medium text-muted-foreground transition hover:text-primary">← Retour aux candidats</a>
        <p class="mt-3 text-xs font-semibold uppercase tracking-[.16em] text-primary">Dossier apprenant</p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-foreground sm:text-3xl">Ajouter un candidat</h2>
        <p class="mt-1 text-sm text-muted-foreground">Complétez les informations principales du dossier.</p>
    </div>
    @include('students.form')
</div>
@endsection
