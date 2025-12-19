@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-8">

    <!-- HEADER -->
    <div>
        <h2 class="text-3xl font-bold text-foreground">Tableau de bord</h2>
        <p class="text-muted-foreground">Bienvenue dans le système de gestion de Must Négoce Academy</p>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

        <!-- Total Students -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-muted-foreground">Total Candidats
                </h3>

                <!-- USERS ICON -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div class="text-3xl font-bold mt-2">{{ number_format($stats['total_students']) }}</div>
            <p class="text-xs text-secondary mt-1">
                <strong>+12%</strong> par rapport au mois dernier
            </p>
        </div>

        <!-- Total Formations -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-muted-foreground">Total Formations</h3>

                <!-- BOOK ICON -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
            </div>

            <div class="text-3xl font-bold mt-2">{{ number_format($stats['total_formations']) }}</div>
            <p class="text-xs text-secondary mt-1">
                <strong>+3</strong> par rapport au mois dernier
            </p>
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
                @foreach($recentFormations as $formation)
                    <div class="flex justify-between items-start pb-3 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium">{{ $formation->name }}</p>
                            <p class="text-sm text-muted-foreground">Formateur : {{ $formation->trainer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Students -->
        <div class="bg-card border border-border rounded-xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Candidats Récents</h3>
            </div>

            <div class="p-5 space-y-4">
                @foreach($recentStudents as $student)
                    <div class="flex justify-between items-start pb-3 border-b border-border last:border-0">
                        <div>
                            <p class="font-medium">{{ $student->name }}</p>
                            <p class="text-sm text-muted-foreground">{{ $student->formation->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Last 7 Days -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Nouveaux Candidat — 7 Derniers Jours</h3>
            <canvas id="chartDays" height="120"></canvas>
        </div>

        <!-- Last 12 Months -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Candidat — 12 Derniers Mois</h3>
            <canvas id="chartMonths" height="120"></canvas>
        </div>

        <!-- By City -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Candidat par Ville</h3>
            <canvas id="chartCity" height="150"></canvas>
        </div>

        <!-- By Status -->
        <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Candidat par Statut</h3>
            <canvas id="chartStatus" height="150"></canvas>
        </div>

        <!-- By Year -->
        <div class="lg:col-span-2 bg-card border border-border rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Candidat par Année</h3>
            <canvas id="chartYear" height="130"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chartDays'), {
    type: 'line',
    data: {
        labels: @json($days),
        datasets: [{
            label: 'Nouveaux étudiants',
            data: @json($dayTotals),
            tension: 0.35,
            borderWidth: 2
        }]
    }
});

new Chart(document.getElementById('chartMonths'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'candida',
            data: @json($monthTotals),
            borderWidth: 1
        }]
    }
});

new Chart(document.getElementById('chartCity'), {
    type: 'pie',
    data: {
        labels: @json($cityLabels),
        datasets: [{ data: @json($cityTotals) }]
    }
});

new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
        labels: @json($statusLabels),
        datasets: [{ data: @json($statusTotals) }]
    }
});

new Chart(document.getElementById('chartYear'), {
    type: 'bar',
    data: {
        labels: @json($yearLabels),
        datasets: [{
            label: 'candida',
            data: @json($yearTotals),
            borderWidth: 1
        }]
    }
});
</script>
@endpush

@endsection
