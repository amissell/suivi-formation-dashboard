@extends('layouts.app')

@section('title', 'Dashboard - Must Negoce Academy')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Students -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Total Candidats</p>
                    <p class="text-2xl font-bold text-foreground">{{ $stats['total_students'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs mt-2 {{ $stats['student_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $stats['student_change'] >= 0 ? '+' : '' }}{{ $stats['student_change'] }}% ce mois
            </p>
        </div>
        
        <!-- Total Formations -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Total Formations</p>
                    <p class="text-2xl font-bold text-foreground">{{ $stats['total_formations'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="text-xs mt-2 {{ $stats['formation_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $stats['formation_change'] >= 0 ? '+' : '' }}{{ $stats['formation_change'] }}% ce mois
            </p>
        </div>
        
        <!-- Total Revenue -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Revenus Totaux</p>
                    <p class="text-2xl font-bold text-foreground">{{ number_format($financial['total_revenue'], 0, ',', ' ') }} DH</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs mt-2 text-muted-foreground">
                {{ $financial['students_paid'] }} candidats payes
            </p>
        </div>
        
        <!-- Remaining Payments -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Paiements Restants</p>
                    <p class="text-2xl font-bold text-foreground">{{ number_format($financial['total_remaining'], 0, ',', ' ') }} DH</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs mt-2 text-orange-600">
                {{ $financial['students_unpaid'] }} candidats en attente
            </p>
        </div>
    </div>
    
    <!-- Alerts Section -->
    @if(count($alerts) > 0)
    <div class="space-y-2">
        @foreach($alerts as $alert)
        <div class="flex items-center gap-3 p-3 rounded-lg {{ $alert['type'] === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-blue-50 border border-blue-200' }}">
            <span class="{{ $alert['type'] === 'warning' ? 'text-yellow-600' : 'text-blue-600' }}">
                @if($alert['icon'] === 'money')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                    </svg>
                @endif
            </span>
            <a href="{{ $alert['link'] }}" class="text-sm {{ $alert['type'] === 'warning' ? 'text-yellow-800' : 'text-blue-800' }} hover:underline">
                {{ $alert['message'] }}
            </a>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Students by Day Chart -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Candidats (7 derniers jours)</h3>
            <canvas id="dailyChart" height="200"></canvas>
        </div>
        
        <!-- Revenue Chart -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Revenus Mensuels</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        
        <!-- Students by City -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Candidats par Ville</h3>
            <canvas id="cityChart" height="200"></canvas>
        </div>
        
        <!-- Students by Year -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Candidats par Annee</h3>
            <canvas id="yearChart" height="200"></canvas>
        </div>
    </div>
    
    <!-- Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Formations -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Formations Recentes</h3>
            <div class="space-y-3">
                @forelse($recentFormations as $formation)
                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                    <div>
                        <p class="font-medium text-foreground">{{ $formation->name }}</p>
                        <p class="text-sm text-muted-foreground">{{ $formation->trainer }}</p>
                    </div>
                    <span class="text-sm font-medium text-primary">{{ number_format($formation->price, 0, ',', ' ') }} DH</span>
                </div>
                @empty
                <p class="text-muted-foreground text-sm">Aucune formation recente</p>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Students -->
        <div class="bg-card border border-border rounded-lg p-4 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Candidats Recents</h3>
            <div class="space-y-3">
                @forelse($recentStudents as $student)
                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                    <div>
                        <p class="font-medium text-foreground">{{ $student->first_name }} {{ $student->last_name }}</p>
                        <p class="text-sm text-muted-foreground">{{ $student->formation->name ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $student->payment_remaining <= 0 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                        {{ $student->payment_remaining <= 0 ? 'Paye' : 'En attente' }}
                    </span>
                </div>
                @empty
                <p class="text-muted-foreground text-sm">Aucun candidat recent</p>
                @endforelse
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Students Chart
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: @json($days),
            datasets: [{
                label: 'Candidats',
                data: @json($dayTotals),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    
    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Revenus (DH)',
                data: @json($revenueTotals),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });
    
    // City Chart
    new Chart(document.getElementById('cityChart'), {
        type: 'doughnut',
        data: {
            labels: @json($cityLabels),
            datasets: [{
                data: @json($cityTotals),
                backgroundColor: [
                    '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981',
                    '#6366F1', '#14B8A6', '#F97316', '#84CC16', '#06B6D4'
                ]
            }]
        },
        options: { responsive: true }
    });
    
    // Year Chart
    new Chart(document.getElementById('yearChart'), {
        type: 'bar',
        data: {
            labels: @json($yearLabels),
            datasets: [{
                label: 'Candidats',
                data: @json($yearTotals),
                backgroundColor: 'rgba(139, 92, 246, 0.5)',
                borderColor: 'rgb(139, 92, 246)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush
