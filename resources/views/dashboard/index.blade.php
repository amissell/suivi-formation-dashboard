@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="mx-auto max-w-7xl space-y-7 pb-8">
    {{-- Page intro and shortcuts --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-950 via-blue-950 to-blue-800 px-6 py-7 text-white shadow-lg sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-12 -top-24 h-72 w-72 rounded-full border-[35px] border-white/5"></div>
        <div class="pointer-events-none absolute right-24 -bottom-32 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"></div>
        <div class="relative flex flex-col justify-between gap-7 lg:flex-row lg:items-end">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-medium text-blue-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Espace administration
                </div>
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Bonjour, {{ auth()->user()->name ?? 'Admin' }}</h2>
                <p class="mt-2 max-w-xl text-sm text-blue-100/80 sm:text-base">Voici un aperçu de votre activité et des chiffres clés de Must Négoce Academy.</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('students.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-400 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
                    Ajouter un candidat
                </a>
                <a href="{{ route('groups.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white/40">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
                    Créer un groupe
                </a>
            </div>
        </div>
        <div class="relative mt-7 flex items-center gap-2 border-t border-white/10 pt-4 text-xs text-blue-100/75">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="17" rx="2" stroke-width="1.7"/><path d="M16 2v4M8 2v4M3 10h18" stroke-width="1.7" stroke-linecap="round"/></svg>
            {{ now()->locale('fr')->translatedFormat('l d F Y') }}
        </div>
    </section>

    {{-- Alerts --}}
    @if(!empty($alerts) && count($alerts) > 0)
        <section aria-label="Alertes" class="space-y-2.5">
            @foreach($alerts as $alert)
                @php
                    $alertType = $alert['type'] ?? '';
                    $alertStyle = match($alertType) {
                        'warning' => 'border-amber-200 bg-amber-50 text-amber-950 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-100',
                        'danger' => 'border-red-200 bg-red-50 text-red-950 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-100',
                        default => 'border-blue-200 bg-blue-50 text-blue-950 dark:border-blue-900/60 dark:bg-blue-950/30 dark:text-blue-100',
                    };
                    $alertIconStyle = $alertType === 'warning' ? 'text-amber-600 dark:text-amber-300' : ($alertType === 'danger' ? 'text-red-600 dark:text-red-300' : 'text-blue-600 dark:text-blue-300');
                @endphp
                <div class="flex items-start gap-3 rounded-xl border px-4 py-3 {{ $alertStyle }}">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $alertIconStyle }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <p class="flex-1 text-sm font-medium">{{ $alert['message'] ?? '' }}</p>
                    @if(!empty($alert['link']))
                        <a href="{{ $alert['link'] }}" class="shrink-0 text-sm font-semibold underline decoration-current/30 underline-offset-4 hover:decoration-current">Consulter <span aria-hidden="true">→</span></a>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    {{-- KPI cards --}}
    <section aria-label="Indicateurs clés" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $studentChange = $stats['student_change'];
            $formationChange = $stats['formation_change'];
            $cards = [
                ['key' => 'total_students', 'label' => 'Candidats', 'value' => number_format($stats['total_students']), 'change' => $studentChange, 'hint' => 'vs mois dernier', 'icon' => 'users', 'tone' => 'blue'],
                ['key' => 'total_formations', 'label' => 'Formations', 'value' => number_format($stats['total_formations']), 'change' => $formationChange, 'hint' => 'vs mois dernier', 'icon' => 'book', 'tone' => 'violet'],
                ['key' => 'total_revenue', 'label' => 'Revenus encaissés', 'value' => number_format($financial['total_revenue']) . ' DH', 'hint' => 'Total encaissé', 'icon' => 'wallet', 'tone' => 'emerald'],
                ['key' => 'total_remaining', 'label' => 'Paiements restants', 'value' => number_format($financial['total_remaining']) . ' DH', 'hint' => number_format($financial['students_unpaid']) . ' candidat(s) à suivre', 'icon' => 'clock', 'tone' => 'amber'],
            ];
        @endphp
        @foreach($cards as $card)
            @php
                $toneClasses = [
                    'blue' => 'bg-blue-50 text-blue-700 ring-blue-100 dark:bg-blue-400/10 dark:text-blue-300 dark:ring-blue-400/10',
                    'violet' => 'bg-violet-50 text-violet-700 ring-violet-100 dark:bg-violet-400/10 dark:text-violet-300 dark:ring-violet-400/10',
                    'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100 dark:bg-emerald-400/10 dark:text-emerald-300 dark:ring-emerald-400/10',
                    'amber' => 'bg-amber-50 text-amber-700 ring-amber-100 dark:bg-amber-400/10 dark:text-amber-300 dark:ring-amber-400/10',
                ][$card['tone']];
            @endphp
            <article class="rounded-xl border border-border bg-card p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">{{ $card['label'] }}</p>
                        <p class="mt-3 text-2xl font-bold tracking-tight text-foreground sm:text-3xl" data-dashboard-value="{{ $card['key'] }}">{{ $card['value'] }}</p>
                    </div>
                    <div class="rounded-xl p-2.5 ring-1 {{ $toneClasses }}">
                        @if($card['icon'] === 'users')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-11a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @elseif($card['icon'] === 'book')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @elseif($card['icon'] === 'wallet')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 8V5a2 2 0 0 0-2-2H5a3 3 0 0 0 0 6h15v10a2 2 0 0 1-2 2H5a3 3 0 0 1-3-3V6m18 5h-4a2 2 0 0 0 0 4h4v-4Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="1.8"/><path d="M12 7v5l3 2" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex min-h-5 items-center gap-1.5 text-xs text-muted-foreground">
                    @if(isset($card['change']))
                        <span class="font-semibold {{ $card['change'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $card['change'] >= 0 ? '↑' : '↓' }} {{ abs($card['change']) }}%</span>
                    @endif
                    <span @if($card['key'] === 'total_remaining') data-dashboard-unpaid-count @endif>{{ $card['hint'] }}</span>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Analytics --}}
    <section>
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-foreground">Vue d’ensemble</h3>
                <p class="text-sm text-muted-foreground">Suivez l’évolution des inscriptions et des finances.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span id="dashboard-updated" class="text-xs text-muted-foreground">Données mises à jour à l’ouverture de la page</span>
                <button id="dashboard-refresh" type="button" class="inline-flex items-center gap-2 rounded-lg border border-border bg-card px-3 py-2 text-xs font-semibold text-foreground transition hover:bg-muted disabled:cursor-wait disabled:opacity-60">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 7v5h-5M4 17v-5h5m-3.5-4a7 7 0 0 1 11.6-2L20 12M4 12l2.9 5a7 7 0 0 0 11.6-2" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Actualiser
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            @foreach([
                ['id' => 'chartDays', 'title' => 'Nouvelles inscriptions', 'subtitle' => 'Activité des 7 derniers jours'],
                ['id' => 'chartMonths', 'title' => 'Inscriptions par mois', 'subtitle' => 'Évolution sur les 12 derniers mois'],
                ['id' => 'chartRevenue', 'title' => 'Revenus encaissés', 'subtitle' => 'Par mois, en dirhams'],
                ['id' => 'chartCity', 'title' => 'Répartition par ville', 'subtitle' => 'Origine des candidats'],
                ['id' => 'chartYear', 'title' => 'Inscriptions par année', 'subtitle' => 'Historique des inscriptions'],
            ] as $chart)
                <article class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
                    <div class="mb-5">
                        <h4 class="font-semibold text-foreground">{{ $chart['title'] }}</h4>
                        <p class="mt-1 text-xs text-muted-foreground">{{ $chart['subtitle'] }}</p>
                    </div>
                    <div class="relative h-64 sm:h-72">
                        <canvas id="{{ $chart['id'] }}" aria-label="{{ $chart['title'] }}"></canvas>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Chart) return;

    const gridColor = 'rgba(148, 163, 184, 0.16)';
    const tickColor = document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b';
    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: tickColor, usePointStyle: true, boxWidth: 8, padding: 18 } },
            tooltip: { mode: 'index', intersect: false, padding: 10, cornerRadius: 8 }
        },
        scales: {
            x: { grid: { display: false }, ticks: { color: tickColor } },
            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, precision: 0 } }
        }
    };
    const create = (id, config) => {
        const element = document.getElementById(id);
        if (element) new Chart(element, config);
    };

    create('chartDays', {
        type: 'line',
        data: { labels: @json($days), datasets: [{ label: 'Candidats', data: @json($dayTotals), tension: .38, fill: true, borderWidth: 2.5, borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, .10)', pointBackgroundColor: '#2563eb', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 5 }] },
        options: { ...baseOptions, interaction: { mode: 'index', intersect: false } }
    });

    create('chartMonths', {
        type: 'bar',
        data: { labels: @json($months), datasets: [{ label: 'Candidats', data: @json($monthTotals), borderRadius: 5, maxBarThickness: 28, backgroundColor: 'rgba(99, 102, 241, .75)' }] },
        options: baseOptions
    });

    create('chartRevenue', {
        type: 'bar',
        data: { labels: @json($revenueLabels), datasets: [{ label: 'Revenus (DH)', data: @json($revenueTotals), borderRadius: 5, maxBarThickness: 28, backgroundColor: 'rgba(16, 185, 129, .75)' }] },
        options: { ...baseOptions, scales: { ...baseOptions.scales, y: { ...baseOptions.scales.y, ticks: { color: tickColor, callback: value => Number(value).toLocaleString() + ' DH' } } } }
    });

    create('chartCity', {
        type: 'doughnut',
        data: { labels: @json($cityLabels), datasets: [{ data: @json($cityTotals), backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', '#64748b', '#84cc16', '#6366f1'], borderWidth: 0, hoverOffset: 5 }] },
        options: { ...baseOptions, cutout: '64%', scales: {}, plugins: { ...baseOptions.plugins, legend: { position: 'bottom', labels: { color: tickColor, usePointStyle: true, boxWidth: 8, padding: 14 } } } }
    });

    create('chartYear', {
        type: 'bar',
        data: { labels: @json($yearLabels), datasets: [{ label: 'Candidats', data: @json($yearTotals), borderRadius: 5, maxBarThickness: 34, backgroundColor: 'rgba(245, 158, 11, .75)' }] },
        options: baseOptions
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('dashboard-refresh');
    const updated = document.getElementById('dashboard-updated');
    if (!button) return;

    button.addEventListener('click', async () => {
        button.disabled = true;
        const originalLabel = button.innerHTML;
        button.textContent = 'Actualisation…';

        try {
            const response = await fetch(@json(route('dashboard.refresh')), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Refresh failed');
            const data = await response.json();
            const number = value => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(Number(value) || 0);

            document.querySelector('[data-dashboard-value="total_students"]').textContent = number(data.stats.total_students);
            document.querySelector('[data-dashboard-value="total_formations"]').textContent = number(data.stats.total_formations);
            document.querySelector('[data-dashboard-value="total_revenue"]').textContent = number(data.financial.total_revenue) + ' DH';
            document.querySelector('[data-dashboard-value="total_remaining"]').textContent = number(data.financial.total_remaining) + ' DH';
            document.querySelector('[data-dashboard-unpaid-count]').textContent = number(data.financial.students_unpaid) + ' candidat(s) à suivre';
            if (updated) updated.textContent = 'Mis à jour à ' + data.updated_at;
        } catch (error) {
            if (updated) updated.textContent = 'Actualisation impossible. Réessayez.';
        } finally {
            button.innerHTML = originalLabel;
            button.disabled = false;
        }
    });
});
</script>
@endpush
