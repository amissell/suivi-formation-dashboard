@extends('layouts.app')

@section('title', 'Formations')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Formations</h2>
            <p class="text-muted-foreground">Manage your academy formations</p>
        </div>
        <button onclick="openModal('add')" 
                class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-md font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Formation
        </button>
    </div>

    <!-- Formations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($formations as $formation)
        <div class="bg-card border border-border rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 flex flex-col justify-between">
            <div>
                <h3 class="text-xl font-semibold text-foreground">{{ $formation->name }}</h3>
                <p class="text-muted-foreground mt-1">Trainer: {{ $formation->trainer }}</p>
                @if(isset($formation->description))
                    <p class="text-muted-foreground mt-2">{{ $formation->description }}</p>
                @endif
                @if(isset($formation->duration)) 
                    <p class="text-muted-foreground mt-1">Duration: {{ $formation->duration }}</p>
                @endif
                @if(isset($formation->enrolled) && isset($formation->capacity))
                    <p class="text-muted-foreground mt-1">Enrolled: {{ $formation->enrolled }}/{{ $formation->capacity }}</p>
                @endif
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                <button onclick="openModal('edit', @json($formation)))" 
                        class="inline-flex items-center justify-center w-9 h-9 border border-input bg-background hover:bg-accent hover:text-accent-foreground rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <form action="{{ route('formations.destroy', $formation) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')"
                    class="inline-flex items-center justify-center w-9 h-9 border border-input bg-background hover:bg-red-600 hover:text-white rounded-md">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Include -->
@include('formations.modal')

@push('scripts')
<script src="{{ asset('js/formations.js') }}"></script>
@endpush
@endsection
