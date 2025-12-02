@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-bold text-foreground">Dashboard</h2>
        <p class="text-muted-foreground">Welcome to Must Négoce Academy management system</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Students -->
        <div class="bg-card border border-border rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-sm font-medium text-muted-foreground">Total Students</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-foreground">{{ number_format($stats['total_students']) }}</div>
            <p class="text-xs text-secondary mt-1">
                <span class="font-medium">+12%</span> from last month
            </p>
        </div>

        <!-- Total Formations -->
        <div class="bg-card border border-border rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-sm font-medium text-muted-foreground">Total Formations</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-foreground">{{ number_format($stats['total_formations']) }}</div>
            <p class="text-xs text-secondary mt-1">
                <span class="font-medium">+3</span> from last month
            </p>
        </div>

        <!-- Graduated -->
        <div class="bg-card border border-border rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-sm font-medium text-muted-foreground">Graduated</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
            </div>
            <div class="text-2xl font-bold text-foreground">{{ $stats['graduated'] }}</div>
            <p class="text-xs text-secondary mt-1">
                <span class="font-medium">+8%</span> from last month
            </p>
        </div>

        <!-- Success Rate -->
        <div class="bg-card border border-border rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between pb-2">
                <h3 class="text-sm font-medium text-muted-foreground">Success Rate</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                    <polyline points="16 7 22 7 22 13"></polyline>
                </svg>
            </div>
            <div class="text-2xl font-bold text-foreground">{{ $stats['success_rate'] }}%</div>
            <p class="text-xs text-secondary mt-1">
                <span class="font-medium">+2%</span> from last month
            </p>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Formations -->
        <div class="bg-card border border-border rounded-lg">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Recent Formations</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentFormations as $formation)
                    <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium text-foreground">{{ $formation->name }}</p>
                            <p class="text-sm text-muted-foreground">Trainer: {{ $formation->trainer }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="bg-card border border-border rounded-lg">
            <div class="p-6 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Recent Students</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentStudents as $student)
                    <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium text-foreground">{{ $student->name }}</p>
                            <p class="text-sm text-muted-foreground">{{ $student->formation->name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Students per Day Chart -->
    <div class="bg-card border border-border rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-foreground mb-4">Students in Last 7 Days</h3>
        <canvas id="studentsChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('studentsChart').getContext('2d');
const labels = @json($studentsPerDay->pluck('date'));
const data = @json($studentsPerDay->pluck('total'));

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'New Students',
            data: data,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: { display: true, text: 'New Students per Day' }
        }
    }
});
</script>
@endpush

@endsection
