@extends('layouts.app')

@section('title', 'Candidats')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Candidats</h1>
            <p class="text-gray-500 dark:text-gray-400">Gérer les candidats de l'académie</p>
        </div>
        <button onclick="openModal('add')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajouter un candidat
        </button>
    </div>

    <!-- Import/Export Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" required 
                       class="text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Importer Excel
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <form method="GET" id="filter-form">
            <!-- Top Bar: Search + Actions -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row gap-3">
                    <!-- Search Input -->
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" 
                               name="search" 
                               id="search-input"
                               value="{{ request('search') }}"
                               placeholder="🔍 Rechercher par nom, CIN, téléphone, email..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <!-- Filter Toggle Button -->
                        <button type="button" 
                                onclick="toggleFilters()" 
                                id="filter-toggle-btn"
                                class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="font-medium">Filtres</span>
                            @if(request()->hasAny(['formation_id', 'city', 'payment_status', 'date_from', 'date_to']))
                                <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                    {{ collect([request('formation_id'), request('city'), request('payment_status'), request('date_from'), request('date_to')])->filter()->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Search Button -->
                        <button type="submit" 
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Rechercher</span>
                        </button>

                        <!-- Reset Button -->
                        <a href="{{ route('students.index') }}" 
                           class="flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Réinitialiser</span>
                        </a>

                        <!-- Export Excel Button -->
                        <button type="submit"
                                formaction="{{ route('students.export') }}" 
                                formmethod="GET"
                                class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="hidden sm:inline">Excel</span>
                        </button>
                    </div>
                </div>

                <!-- Active Filters Chips -->
                @if(request()->hasAny(['formation_id', 'city', 'payment_status', 'date_from', 'date_to', 'search']))
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Filtres actifs:</span>

                    @if(request('search'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium">
                        Recherche: "{{ Str::limit(request('search'), 20) }}"
                        <button type="button" onclick="removeFilter('search')" class="hover:bg-blue-200 dark:hover:bg-blue-800 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if(request('formation_id'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full text-xs font-medium">
                        Formation: {{ $formations->find(request('formation_id'))->name ?? 'N/A' }}
                        <button type="button" onclick="removeFilter('formation_id')" class="hover:bg-purple-200 dark:hover:bg-purple-800 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if(request('city'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">
                        Ville: {{ request('city') }}
                        <button type="button" onclick="removeFilter('city')" class="hover:bg-green-200 dark:hover:bg-green-800 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if(request('payment_status'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full text-xs font-medium">
                        Paiement: {{ request('payment_status') == 'paid' ? 'Payé' : 'Non payé' }}
                        <button type="button" onclick="removeFilter('payment_status')" class="hover:bg-orange-200 dark:hover:bg-orange-800 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </span>
                    @endif

                    @if(request('date_from') || request('date_to'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-medium">
                        Période: {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '...' }} 
                        → {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : '...' }}
                        <button type="button" onclick="removeDateFilters()" class="hover:bg-indigo-200 dark:hover:bg-indigo-800 rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Advanced Filters Panel (Collapsible) -->
            <div id="advanced-filters" class="hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="p-6 space-y-6">
                    <!-- Section Title -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Filtres Avancés
                        </h3>
                        <button type="button" 
                                onclick="clearAllFilters()"
                                class="text-xs font-medium text-red-600 hover:text-red-700 hover:underline">
                            Effacer tous les filtres
                        </button>
                    </div>

                    <!-- Filter Groups -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Formation Filter -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Formation
                            </label>
                            <select name="formation_id" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Toutes les formations</option>
                                @foreach($formations as $formation)
                                <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                                    {{ $formation->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City Filter -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Ville
                            </label>
                            <select name="city" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Status Filter -->
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Statut Paiement
                            </label>
                            <select name="payment_status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tous les paiements</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✓ Payé complètement</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>⚠ Reste à payer</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Section -->
                    <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Période d'inscription
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Date de début</span>
                                <input type="date" 
                                       name="date_from" 
                                       value="{{ request('date_from') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div class="space-y-1.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Date de fin</span>
                                <input type="date" 
                                       name="date_to" 
                                       value="{{ request('date_to') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Apply Filters Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                onclick="clearAllFilters()"
                                class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 border border-red-200 hover:border-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            Effacer tout
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Appliquer les filtres
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span id="results-count" class="font-medium text-gray-900 dark:text-white">{{ $students->total() }}</span> candidat(s) trouvé(s)
        </p>
    </div>

    <!-- Students Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Candidat</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Formation</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Attestation</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Paiement</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Ville</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <!-- Student Info (Name + CIN + Avatar) -->
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 flex items-center justify-center font-semibold text-sm text-blue-600 dark:text-blue-400 shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                        {{ $student->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                        {{ $student->cin }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Contact Info -->
                        <td class="py-3 px-4">
                            <div class="space-y-1">
                                <p class="text-xs text-gray-900 dark:text-white flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $student->phone }}
                                </p>
                                @if($student->email)
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 truncate max-w-[180px]">
                                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="truncate">{{ $student->email }}</span>
                                </p>
                                @endif
                            </div>
                        </td>

                        <!-- Formation -->
                        <td class="py-3 px-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->formation->name ?? '-' }}</p>
                        </td>

                        <!-- Start Date -->
                        <td class="py-3 px-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ optional($student->start_date)->format('d/m/Y') ?? '-' }}
                            </p>
                        </td>
                        
                        <!-- Attestation Status -->
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1">
                                @if($student->attestation === 'yes')
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-xs text-green-600 font-medium">Oui</span>
                                @else
                                    <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                    </svg>
                                    <span class="text-xs text-red-500 font-medium">Non</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Payment Info with Progress Bar -->
                        <td class="py-3 px-4">
                            <div class="space-y-2 min-w-[180px]">
                                @php
                                    $engagement = $student->engagement ?? 0;
                                    $paid = $student->payment_done ?? 0;
                                    $remaining = $student->payment_remaining ?? 0;
                                    $percentage = $engagement > 0 ? ($paid / $engagement) * 100 : 0;
                                @endphp
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-green-600 h-1.5 rounded-full transition-all" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                
                                <!-- Payment Details -->
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-green-600 font-semibold">
                                        {{ number_format($paid, 0) }} DH
                                    </span>
                                    <span class="text-gray-400">/</span>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                                        {{ number_format($engagement, 0) }} DH
                                    </span>
                                </div>
                                
                                @if($remaining > 0)
                                <p class="text-xs text-orange-600 font-medium">
                                    Reste: {{ number_format($remaining, 0) }} DH
                                </p>
                                @else
                                <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    Soldé
                                </p>
                                @endif
                            </div>
                        </td>

                        <!-- City -->
                        <td class="py-3 px-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->city ?? '-' }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-4">
                            <div class="flex gap-1">
                                @if(!is_null($student->notes) && trim($student->notes) !== '')
                                <button type="button"
                                        onclick='showNotes(@json($student->notes), @json($student->name))'
                                        class="p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-500 hover:text-blue-600 transition-colors"
                                        title="Voir notes">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586"/>
                                    </svg>
                                </button>
                                @endif

                                <!-- Edit Button -->
                                <button onclick='openModal("edit", @json($student))' 
                                        class="p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-500 hover:text-blue-600 transition-colors"
                                        title="Modifier">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ addslashes($student->name) }} ?')" 
                                            class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 hover:text-red-600 transition-colors"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($students->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Aucun candidat trouvé</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Commencez par ajouter votre premier candidat</p>
            </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($students->hasPages())
        <div id="pagination-container" class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $students->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Student Modal -->
<div id="student-modal" class="fixed inset-0 hidden bg-black/60 backdrop-blur-sm flex justify-center items-center p-4 z-50">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl w-full max-w-3xl shadow-2xl flex flex-col max-h-[90vh] transform transition-all scale-95 opacity-0" id="modal-content">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 rounded-t-xl flex-shrink-0">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">Ajouter Candidat</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Remplissez les informations du candidat</p>
            </div>
            <button onclick="closeStudentModal()" type="button" 
                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Body -->
        <div class="overflow-y-auto p-6 flex-1" style="min-height: 0;">
            <form method="POST" action="{{ route('students.store') }}" id="student-form" class="space-y-6">
                @csrf
                
                <!-- Section: Informations Personnelles -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations Personnelles
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nom complet *</label>
                            <input type="text" name="name" placeholder="Ex: Ahmed Benali" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">CIN *</label>
                            <input type="text" name="cin" placeholder="Ex: AB123456" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Téléphone *</label>
                            <input type="text" name="phone" placeholder="Ex: 0612345678" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">E-mail</label>
                            <input type="email" name="email" placeholder="Ex: ahmed@example.com" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Ville</label>
                            <input type="text" name="city" placeholder="Ex: Casablanca" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Section: Formation et Statut -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Formation et Statut
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Formation *</label>
                            <select name="formation_id" id="formation_id" 
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Sélectionner une formation</option>
                                @foreach(App\Models\Formation::all() as $formation)
                                <option value="{{ $formation->id }}">{{ $formation->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date de Début *</label>
                            <input type="date" name="start_date" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Attestation *</label>
                            <select name="attestation" 
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="yes">Oui</option>
                                <option value="no">Non</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Paiement -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informations de Paiement
                    </h4>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Engagement (DH) *</label>
                            <input type="number" step="1" name="engagement" id="engagement" placeholder="Ex: 5000"
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Payé (DH)</label>
                            <input type="number" step="1" name="payment_done" id="payment_done" placeholder="Ex: 2000" 
                                   class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Reste (DH)</label>
                            <input type="number" step="1" name="payment_remaining" id="payment_remaining" value="0" readonly 
                                   class="w-full border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-900 rounded-lg px-3 py-2 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Payment Progress Preview -->
                    <div id="payment-preview" class="hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-200">Progression du paiement</span>
                            <span class="text-sm font-bold text-blue-700 dark:text-blue-300" id="payment-percentage">0%</span>
                        </div>
                        <div class="w-full bg-blue-200 dark:bg-blue-900 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" id="payment-progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Section: Notes -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Notes Additionnelles
                    </h4>
                    
                    <div>
                        <textarea name="notes" placeholder="Ajoutez des notes ou remarques sur ce candidat..." 
                                  class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white h-32 resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 rounded-b-xl flex-shrink-0">
            <button type="button" onclick="closeStudentModal()" 
                    class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium text-gray-700 dark:text-gray-300">
                Annuler
            </button>
            <button type="submit" form="student-form" id="submit-btn"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-semibold text-sm flex items-center gap-2">
                <span id="submit-text">💾 Enregistrer</span>
                <svg class="animate-spin h-4 w-4 hidden" id="loading-spinner" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="notes-modal" class="fixed inset-0 hidden bg-black/60 backdrop-blur-sm flex justify-center items-center p-4 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95 opacity-0" id="notes-modal-content">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Notes - <span id="notes-student-name"></span>
            </h3>
            <button onclick="closeNotesModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 max-h-64 overflow-y-auto">
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap" id="notes-content"></p>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <button onclick="closeNotesModal()" 
                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                Fermer
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ============================================
// 1. FILTER FUNCTIONS
// ============================================

function toggleFilters() {
    const filters = document.getElementById('advanced-filters');
    const button = document.getElementById('filter-toggle-btn');
    
    if (filters) {
        filters.classList.toggle('hidden');
        
        if (!filters.classList.contains('hidden')) {
            button?.classList.add('bg-gray-50', 'dark:bg-gray-700');
        } else {
            button?.classList.remove('bg-gray-50', 'dark:bg-gray-700');
        }
    }
}

function removeFilter(filterName) {
    const form = document.getElementById('filter-form');
    const input = form.querySelector(`[name="${filterName}"]`);
    
    if (input) {
        input.value = '';
        form.submit();
    }
}

function removeDateFilters() {
    const form = document.getElementById('filter-form');
    const dateFrom = form.querySelector('[name="date_from"]');
    const dateTo = form.querySelector('[name="date_to"]');
    
    if (dateFrom) dateFrom.value = '';
    if (dateTo) dateTo.value = '';
    
    form.submit();
}

function clearAllFilters() {
    const form = document.getElementById('filter-form');
    
    if (form) {
        form.reset();
        form.submit();
    }
}

// ============================================
// 2. MODAL FUNCTIONS
// ============================================

function openModal(mode, student = null) {
    const modal = document.getElementById('student-modal');
    const modalContent = document.getElementById('modal-content');
    if (!modal) return;

    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    const form = document.getElementById('student-form');
    const title = document.getElementById('modal-title');

    if (mode === 'add') {
        title.textContent = 'Ajouter Candidat';
        form.action = '{{ route("students.store") }}';
        form.reset();

        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        document.getElementById('payment-preview')?.classList.add('hidden');

    } else if (mode === 'edit' && student) {
        title.textContent = 'Modifier Candidat';
        form.action = `/students/${student.id}`;

        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        form.querySelector('input[name="name"]').value = student.name || '';
        form.querySelector('input[name="cin"]').value = student.cin || '';
        form.querySelector('input[name="phone"]').value = student.phone || '';
        form.querySelector('input[name="email"]').value = student.email || '';
        form.querySelector('select[name="formation_id"]').value = student.formation_id || '';
        
        if (student.start_date) {
            form.querySelector('input[name="start_date"]').value = student.start_date.substring(0, 10);
        } else {
            form.querySelector('input[name="start_date"]').value = '';
        }

        form.querySelector('select[name="attestation"]').value = student.attestation || '';
        form.querySelector('input[name="engagement"]').value = Math.round(student.engagement) || '';
        form.querySelector('input[name="payment_done"]').value = Math.round(student.payment_done) || '';
        form.querySelector('input[name="payment_remaining"]').value = Math.round(student.payment_remaining) || '';
        form.querySelector('input[name="city"]').value = student.city || '';
        form.querySelector('textarea[name="notes"]').value = student.notes || '';

        calculateRemaining();
    }
}

function closeStudentModal() {
    const modal = document.getElementById('student-modal');
    const modalContent = document.getElementById('modal-content');
    
    if (modal && modalContent) {
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('student-form').reset();
            document.getElementById('payment-preview')?.classList.add('hidden');
        }, 200);
    }
}

function showNotes(notes, studentName) {
    const modal = document.getElementById('notes-modal');
    const modalContent = document.getElementById('notes-modal-content');
    
    if (!modal || !modalContent) return;
    
    document.getElementById('notes-student-name').textContent = studentName;
    document.getElementById('notes-content').textContent = notes;
    
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeNotesModal() {
    const modal = document.getElementById('notes-modal');
    const modalContent = document.getElementById('notes-modal-content');
    
    if (!modal || !modalContent) return;
    
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// ============================================
// 3. PAYMENT CALCULATION
// ============================================

function calculateRemaining() {
    const engagementInput = document.getElementById('engagement');
    const paymentDoneInput = document.getElementById('payment_done');
    const paymentRemainingInput = document.getElementById('payment_remaining');
    const paymentPreview = document.getElementById('payment-preview');
    const paymentProgressBar = document.getElementById('payment-progress-bar');
    const paymentPercentage = document.getElementById('payment-percentage');

    if (!engagementInput || !paymentDoneInput || !paymentRemainingInput) return;

    const engagement = parseFloat(engagementInput.value) || 0;
    const paymentDone = parseFloat(paymentDoneInput.value) || 0;
    const remaining = engagement - paymentDone;

    paymentRemainingInput.value = remaining >= 0 ? Math.round(remaining) : 0;

    if (engagement > 0 && paymentPreview && paymentProgressBar && paymentPercentage) {
        const percentage = Math.min((paymentDone / engagement) * 100, 100);
        paymentProgressBar.style.width = percentage + '%';
        paymentPercentage.textContent = Math.round(percentage) + '%';
        paymentPreview.classList.remove('hidden');
    } else if (paymentPreview) {
        paymentPreview.classList.add('hidden');
    }
}

// ============================================
// 4. EVENT LISTENERS & INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ Students page JavaScript loaded');

    // Payment Calculation
    const engagementInput = document.getElementById('engagement');
    const paymentDoneInput = document.getElementById('payment_done');

    if (engagementInput) {
        engagementInput.addEventListener('input', calculateRemaining);
    }

    if (paymentDoneInput) {
        paymentDoneInput.addEventListener('input', calculateRemaining);
    }

    // Form Submit Loading State
    const studentForm = document.getElementById('student-form');
    if (studentForm) {
        studentForm.addEventListener('submit', function() {
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            
            if (submitBtn && submitText && loadingSpinner) {
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
            }
        });
    }

    // Keyboard Shortcuts
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeStudentModal();
            closeNotesModal();
        }
    });

    // Click Outside to Close Modals
    const studentModal = document.getElementById('student-modal');
    if (studentModal) {
        studentModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeStudentModal();
            }
        });
    }

    const notesModal = document.getElementById('notes-modal');
    if (notesModal) {
        notesModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeNotesModal();
            }
        });
    }

    // Auto-open Filters if Active
    const urlParams = new URLSearchParams(window.location.search);
    const hasActiveFilters = 
        urlParams.has('formation_id') ||
        urlParams.has('city') ||
        urlParams.has('payment_status') ||
        urlParams.has('date_from') ||
        urlParams.has('date_to');
    
    if (hasActiveFilters) {
        const advancedFilters = document.getElementById('advanced-filters');
        if (advancedFilters) {
            advancedFilters.classList.remove('hidden');
        }
    }

    console.log('✅ All event listeners initialized');
});
</script>
@endpush