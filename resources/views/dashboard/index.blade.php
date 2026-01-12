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
            @php
                $alertType = isset($alert['type']) ? $alert['type'] : '';
                $alertClasses = '';
                if ($alertType === 'warning') {
                    $alertClasses = 'bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:border-orange-800';
                } elseif ($alertType === 'info') {
                    $alertClasses = 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800';
                } elseif ($alertType === 'danger') {
                    $alertClasses = 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800';
                }
            @endphp

            <div class="flex items-center gap-3 p-3 rounded-lg border {{ $alertClasses }}">
                <div class="flex-shrink-0">
                    @if(isset($alert['icon']) && $alert['icon'] === 'money')
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    @elseif(isset($alert['icon']) && $alert['icon'] === 'users')
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                    @endif
                </div>

                <p class="flex-1 text-sm font-medium text-foreground">{{ isset($alert['message']) ? $alert['message'] : '' }}</p>

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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Candidats -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Total Candidats</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                @php
                    $studentChange = $stats['student_change'];
                    $isPositive = $studentChange >= 0;
                    $arrow = $isPositive ? '↑' : '↓';
                    $colorClass = $isPositive ? 'text-green-600' : 'text-red-600';
                @endphp
                <span class="{{ $colorClass }} font-semibold">
                    {{ $arrow }} {{ abs($studentChange) }}%
                </span>
                <span class="text-muted-foreground">vs mois dernier</span>
            </p>
        </div>

        <!-- Total Formations -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Total Formations</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-foreground">
                {{ number_format($stats['total_formations']) }}
            </div>
            <p class="text-sm mt-2 flex items-center gap-1">
                @php
                    $formationChange = $stats['formation_change'];
                    $isPositive = $formationChange >= 0;
                    $arrow = $isPositive ? '↑' : '↓';
                    $colorClass = $isPositive ? 'text-green-600' : 'text-red-600';
                @endphp
                <span class="{{ $colorClass }} font-semibold">
                    {{ $arrow }} {{ abs($formationChange) }}%
                </span>
                <span class="text-muted-foreground">vs mois dernier</span>
            </p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Revenus Totaux</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-foreground">
                {{ number_format($financial['total_revenue']) }} DH
            </div>
            <p class="text-sm mt-2 text-muted-foreground">
                Total encaissé
            </p>
        </div>

        <!-- Remaining Payments -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-muted-foreground">Paiements Restants</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-foreground">
                {{ number_format($financial['total_remaining']) }} DH
            </div>
            <p class="text-sm mt-2 text-muted-foreground">
                En attente
            </p>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Last 7 Days Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Derniers 7 jours</h3>
            <div class="h-64">
                <canvas id="chartDays"></canvas>
            </div>
        </div>

        <!-- Last 12 Months Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Derniers 12 mois</h3>
            <div class="h-64">
                <canvas id="chartMonths"></canvas>
            </div>
        </div>

        <!-- By City Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Par ville</h3>
            <div class="h-64">
                <canvas id="chartCity"></canvas>
            </div>
        </div>

        <!-- By Year Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-foreground mb-4">Par année</h3>
            <div class="h-64">
                <canvas id="chartYear"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm lg:col-span-2">
            <h3 class="text-lg font-semibold text-foreground mb-4">Revenus mensuels</h3>
            <div class="h-64">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartColors = {
    primary: 'rgb(59, 130, 246)',
    success: 'rgb(34, 197, 94)',
    warning: 'rgb(249, 115, 22)',
    danger: 'rgb(239, 68, 68)',
    info: 'rgb(99, 102, 241)'
};

// Last 7 Days Chart
new Chart(document.getElementById('chartDays'), {
    type: 'line',
    data: {
        labels: {!! json_encode($days) !!},
        datasets: [{
            label: 'Nouveaux candidats',
            data: {!! json_encode($dayTotals) !!},
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
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// Last 12 Months Chart
new Chart(document.getElementById('chartMonths'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($months) !!},
        datasets: [{
            label: 'Candidats',
            data: {!! json_encode($monthTotals) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.6)',
            borderColor: chartColors.primary,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// By City Chart
new Chart(document.getElementById('chartCity'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($cityLabels) !!},
        datasets: [{
            data: {!! json_encode($cityTotals) !!},
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
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// By Year Chart
new Chart(document.getElementById('chartYear'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($yearLabels) !!},
        datasets: [{
            label: 'Candidats',
            data: {!! json_encode($yearTotals) !!},
            backgroundColor: 'rgba(34, 197, 94, 0.6)',
            borderColor: chartColors.success,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
        labels: {!! json_encode($revenueLabels) !!},
        datasets: [{
            label: 'Revenus (DH)',
            data: {!! json_encode($revenueTotals) !!},
            backgroundColor: 'rgba(34, 197, 94, 0.6)',
            borderColor: chartColors.success,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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