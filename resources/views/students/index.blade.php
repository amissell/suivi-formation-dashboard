@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-foreground">Students</h2>
            <p class="text-muted-foreground">Manage your academy students</p>
        </div>
        <button onclick="openModal('add')" 
                class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-md font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Student
        </button>
    </div>

    <!-- Students Table -->
    <div class="bg-card border border-border rounded-lg shadow-sm">
        <div class="p-6 border-b border-border">
            <h3 class="text-lg font-semibold text-foreground">All Students</h3>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="w-full table-auto">
    <thead>
        <tr class="border-b border-border">
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Name</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">CIN</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Phone</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Email</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Formation</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Start Date</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Status</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Attestation</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Payment Done</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Payment Left</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">City</th>
            <th class="text-left text-sm font-medium text-muted-foreground pb-3">Notes</th>
            <th class="text-right text-sm font-medium text-muted-foreground pb-3">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($students as $student)
        <tr class="border-b border-border last:border-0">
            <td class="py-4 font-medium text-foreground">{{ $student->name }}</td>
            <td class="py-4 text-foreground">{{ $student->cin }}</td>
            <td class="py-4 text-foreground">{{ $student->phone }}</td>
            <td class="py-4 text-foreground">{{ $student->email ?? '-' }}</td>
            <td class="py-4 text-foreground">{{ $student->formation->name ?? '-' }}</td>
            <td class="py-4 text-foreground">{{ \Carbon\Carbon::parse($student->start_date)->format('Y-m-d') }}</td>
            <td class="py-4 text-foreground">{{ ucfirst($student->status) }}</td>
            <td class="py-4 text-foreground">{{ $student->attestation }}</td>
            <td class="py-4 text-foreground">{{ $student->payment_done ?? 0 }}</td>
            <td class="py-4 text-foreground">{{ $student->payment_remaining ?? 0 }}</td>
            <td class="py-4 text-foreground">{{ $student->city ?? '-' }}</td>
            <td class="py-4 text-foreground truncate max-w-xs">
                {{ $student->notes ?? '-' }}
            </td>

            <td class="py-4 text-right flex items-center justify-end gap-2">

    <!-- Edit -->
    <button onclick="openModal('edit', {{ $student }})"
        class="w-9 h-9 flex items-center justify-center rounded-full border border-border 
               hover:bg-primary/10 hover:text-primary transition">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M15 3l6 6M3 21l3-9 9-9 6 6-9 9-9 3z" />
        </svg>
    </button>

    <!-- Delete -->
    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit"
            onclick="return confirm('Are you sure?')"
            class="w-9 h-9 flex items-center justify-center rounded-full border border-border 
                   hover:bg-red-100 hover:text-red-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M19 7L5 7" />
                <path d="M10 11v6M14 11v6" />
                <path d="M9 7V4h6v3" />
                <path d="M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7" />
            </svg>
        </button>
    </form>

</td>

        </tr>
        @endforeach
    </tbody>
</table>

        </div>
    </div>
</div>

<!-- Modal Include -->
@include('students.modal')

@push('scripts')
<script src="{{ asset('js/students.js') }}"></script>
@endpush

@endsection
