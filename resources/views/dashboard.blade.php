@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-8">

    <!-- HEADER -->
    <div>
        <h2 class="text-3xl font-bold text-foreground">Tableau de bord</h2>
        <p class="text-muted-foreground">Bienvenue dans le système de gestion de Must Négoce Academy</p>
    </div>

    <!-- ALERTS -->
    @if(!empty($alerts) && count($alerts) > 0)
    <div class="space-y-3">
        @foreach($alerts as $alert)
        <div class="flex items-center gap-3 p-3 rounded-lg border 
                    {{ $alert['type'] === 'warning' ? 'bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:border-orange-800' : '' }}
                    {{ $alert['type'] === 'info' ? 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800' : '' }}
                    {{ $alert['type'] === 'danger' ? 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800' : '' }}">
            
            <!-- Icon -->
            <div class="flex-shrink-0">
                @if($alert['icon'] === 'money')
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                @elseif($alert['icon'] === 'users')
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                @endif
            </div>

            <p class="flex-1 text-sm font-medium text-foreground">{{ $alert['message'] }}</p>
            
            @if(!empty($alert['link']))
            <a href="{{ $alert['link'] }}" class="text-sm font-medium text-primary hover:underline whitespace-nowrap">
                Voir →
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Total Candidats -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Total Candidats</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            
            <div class="text-3xl font-bold text-foreground">
                {{ number_format($stats['total_students']) }}
            </div>
            
            <p class="text-sm mt-2 flex items-center gap-1">
                <span class="{{ $stats['student_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                    {{ $stats['student_change'] >= 0 ? '↑' : '↓' }}
                    {{ abs($stats['student_change']) }}%
                </span>
                <span class="text-muted-foreground">vs mois dernier</span>
            </p>
        </div>

        <!-- Total Formations -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Total Formations</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            
            <div class="text-3xl font-bold text-foreground">
                {{ number_format($stats['total_formations']) }}
            </div>
            
            <p class="text-sm mt-2 flex items-center gap-1">
                <span class="{{ $stats['formation_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                    {{ $stats['formation_change'] >= 0 ? '↑' : '↓' }}
                    {{ abs($stats['formation_change']) }}%
                </span>
                <span class="text-muted-foreground">vs mois dernier</span>
            </p>
        </div>
    </div>

    <!-- FINANCIAL DASHBOARD -->
    <div class="space-y-6">
        <h3 class="text-xl font-semibold text-foreground">Tableau Financier</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            
            <!-- Total Revenue -->
            <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-muted-foreground">Revenus Totaux</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($financial['total_revenue'], 2) }} DH</div>
            </div>

            <!-- Remaining Payments -->
            <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-muted-foreground">Paiements Restants</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-orange-600">{{ number_format($financial['total_remaining'], 2) }} DH</div>
            </div>

            <!-- Candidats Payés -->
            <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-muted-foreground">Candidats Payés</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-foreground">{{ number_format($financial['students_paid']) }}</div>
            </div>

            <!-- Candidats Non Payés -->
            <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-muted-foreground">Candidats Non Payés</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="15" y1="9" x2="9" y2="15"/>
                        <line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-red-600">{{ number_format($financial['students_unpaid']) }}</div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-foreground">Revenus — 12 Derniers Mois</h3>
            <canvas id="chartRevenue" height="120"></canvas>
        </div>
    </div>

    <!-- RECENT DATA -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Formations -->
        <div class="bg-card border border-border rounded-xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Formations Récentes</h3>
            </div>

            <div class="p-5 space-y-4">
                @forelse($recentFormations as $formation)
                    <div class="flex justify-between items-start pb-3 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium text-foreground">{{ $formation->name }}</p>
                            <p class="text-sm text-muted-foreground">Formateur : {{ $formation->trainer }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground text-center py-4">Aucune formation récente</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Candidats -->
        <div class="bg-card border border-border rounded-xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Candidats Récents</h3>
            </div>

            <div class="p-5 space-y-4">
                @forelse($recentStudents as $student)
                    <div class="flex justify-between items-start pb-3 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium text-foreground">{{ $student->name }}</p>
                            <p class="text-sm text-muted-foreground">{{ $student->formation?->name ?? 'Non assigné' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground text-center py-4">Aucun candidat récent</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Last 7 Days -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-foreground">Nouveaux Candidats — 7 Derniers Jours</h3>
            <canvas id="chartDays" height="120"></canvas>
        </div>

        <!-- Last 12 Months -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-foreground">Candidats — 12 Derniers Mois</h3>
            <canvas id="chartMonths" height="120"></canvas>
        </div>

        <!-- By City -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-foreground">Candidats par Ville</h3>
            <canvas id="chartCity" height="150"></canvas>
        </div>

        <!-- By Year -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4 text-foreground">Candidats par Année</h3>
            <canvas id="chartYear" height="150"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart configuration with better styling
const chartColors = {
    primary: 'rgb(59, 130, 246)',
    success: 'rgb(34, 197, 94)',
    warning: 'rgb(249, 115, 22)',
    danger: 'rgb(239, 68, 68)',
    info: 'rgb(99, 102, 241)'
};

// Last 7 Days
new Chart(document.getElementById('chartDays'), {
    type: 'line',
    data: {
        labels: @json($days),
        datasets: [{
            label: 'Nouveaux candidats',
            data: @json($dayTotals),
            tension: 0.4,
            borderWidth: 2,
            borderColor: chartColors.primary,
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            pointBackgroundColor: chartColors.primary,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// Last 12 Months
new Chart(document.getElementById('chartMonths'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Candidats',
            data: @json($monthTotals),
            backgroundColor: 'rgba(59, 130, 246, 0.6)',
            borderColor: chartColors.primary,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// By City
new Chart(document.getElementById('chartCity'), {
    type: 'pie',
    data: {
        labels: @json($cityLabels),
        datasets: [{
            data: @json($cityTotals),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(249, 115, 22, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(99, 102, 241, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// By Year
new Chart(document.getElementById('chartYear'), {
    type: 'bar',
    data: {
        labels: @json($yearLabels),
        datasets: [{
            label: 'Candidats',
            data: @json($yearTotals),
            backgroundColor: 'rgba(34, 197, 94, 0.6)',
            borderColor: chartColors.success,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// Revenue Chart
new Chart(document.getElementById('chartRevenue'), {
    type: 'bar',
    data: {
        labels: @json($revenueLabels),
        datasets: [{
            label: 'Revenus (DH)',
            data: @json($revenueTotals),
            backgroundColor: 'rgba(34, 197, 94, 0.6)',
            borderColor: chartColors.success,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenus: ' + context.parsed.y.toLocaleString() + ' DH';
                    }
                }
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' DH';
                    }
                }
            }
        }
    }
});
</script>
@endpush

@endsection