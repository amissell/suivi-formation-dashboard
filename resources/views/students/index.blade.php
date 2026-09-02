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
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <form method="GET" id="filter-form" action="{{ route('students.index') }}" class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                       placeholder="🔍 Nom, CIN, téléphone, email..." autocomplete="off"
                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">

                <div class="flex gap-2 shrink-0">
                    <button type="submit" id="filter-submit-btn"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center gap-2">
                        <svg id="filter-spinner" class="hidden animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Rechercher</span>
                    </button>
                    <button type="button" id="reset-filters-btn"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Réinitialiser
                    </button>
                    <button type="submit" formaction="{{ route('students.export') }}" formmethod="GET" formtarget="_blank" data-no-ajax
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                        Excel
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                <select name="formation_id" class="filter-field px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les formations</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                            {{ $formation->name }}
                        </option>
                    @endforeach
                </select>
                <select name="attestation" class="filter-field px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Attestation (tous)</option>
                    <option value="yes" {{ request('attestation') === 'yes' ? 'selected' : '' }}>A obtenu l'attestation</option>
                    <option value="no" {{ request('attestation') === 'no' ? 'selected' : '' }}>N'a pas obtenu</option>
                </select>

                <select name="certificate_generated" class="filter-field px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Certificat (tous)</option>
                    <option value="yes" {{ request('certificate_generated') === 'yes' ? 'selected' : '' }}>A déjà pris le certificat</option>
                    <option value="no" {{ request('certificate_generated') === 'no' ? 'selected' : '' }}>N'a pas encore pris</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       title="Date de début (à partir de)"
                       class="filter-field px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">

                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       title="Date de début (jusqu'à)"
                       class="filter-field px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            @if(request()->hasAny(['search','formation_id','profession','attestation','certificate_generated','date_from','date_to']))
            <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-gray-400 dark:text-gray-500">
                    Filtres actifs — les résultats se mettent à jour automatiquement.
                </span>
            </div>
            @endif
        </form>
    </div>

    @include('students.partials.results')
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Profession / Statut *</label>
                            <select name="status"
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Sélectionner</option>
                                @foreach($professions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="attestation-field">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Attestation *</label>
                            <select name="attestation"
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="yes">Oui</option>
                                <option value="no">Non</option>
                            </select>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Non disponible par défaut à la création — à activer une fois le certificat prêt.</p>
                        </div>

                        <div id="certificate-generated-field">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Certificat Généré *</label>
                            <select name="certificate_generated"
                                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="no">Non</option>
                                <option value="yes">Oui</option>
                            </select>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Cochez "Oui" une fois le certificat imprimé, pour éviter de le regénérer.</p>
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
// 1. AJAX FILTERING (debounced, abortable, history-aware)
// ============================================

const filterForm = document.getElementById('filter-form');
const resultsContainer = () => document.getElementById('students-results');
const filterSubmitBtn = document.getElementById('filter-submit-btn');
const filterSpinner = document.getElementById('filter-spinner');

let currentAbortController = null;
let debounceTimer = null;

function setLoading(isLoading) {
    filterSpinner?.classList.toggle('hidden', !isLoading);
    if (filterSubmitBtn) filterSubmitBtn.disabled = isLoading;
    const container = resultsContainer();
    if (container) {
        container.classList.toggle('opacity-50', isLoading);
        container.classList.toggle('pointer-events-none', isLoading);
    }
}

/**
 * Fetches filtered results via AJAX and swaps the results container.
 * @param {string} url - full URL (with query string) to fetch.
 * @param {boolean} pushState - whether to update the browser URL/history.
 */
async function fetchResults(url, pushState = true) {
    // Cancel any in-flight request so late responses can't clobber newer ones
    if (currentAbortController) currentAbortController.abort();
    currentAbortController = new AbortController();

    setLoading(true);

    try {
        const response = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: currentAbortController.signal,
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const html = await response.text();
        const container = resultsContainer();
        if (container) container.outerHTML = html;

        if (pushState) {
            window.history.pushState({ studentsFilter: true }, '', url);
        }
    } catch (err) {
        if (err.name !== 'AbortError') {
            console.error('Filter fetch failed:', err);
            // Fall back to a normal navigation so the user isn't stuck
            window.location.href = url;
        }
    } finally {
        setLoading(false);
    }
}

function buildFilterUrl() {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value !== '') params.set(key, value);
    }
    const query = params.toString();
    return filterForm.action + (query ? `?${query}` : '');
}

function triggerFilter() {
    fetchResults(buildFilterUrl(), true);
}

function debouncedTriggerFilter(delay = 400) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(triggerFilter, delay);
}

if (filterForm) {
    // Text search: debounce so we don't fetch on every keystroke
    const searchInput = document.getElementById('search-input');
    searchInput?.addEventListener('input', () => debouncedTriggerFilter());

    // Selects/dates: filter immediately on change
    filterForm.querySelectorAll('.filter-field').forEach(field => {
        field.addEventListener('change', () => triggerFilter());
    });

    // Submit button / Enter key: fetch instead of a full page reload,
    // but let the Excel export button do its normal browser submit.
    filterForm.addEventListener('submit', (e) => {
        if (e.submitter && e.submitter.hasAttribute('data-no-ajax')) return; // let Excel export through
        e.preventDefault();
        clearTimeout(debounceTimer);
        triggerFilter();
    });

    document.getElementById('reset-filters-btn')?.addEventListener('click', () => {
        filterForm.reset();
        fetchResults(filterForm.action, true);
    });
}

// Pagination links get replaced along with the results container, so we
// delegate the click handler to a stable ancestor instead of re-binding it.
document.addEventListener('click', (e) => {
    const link = e.target.closest('#students-results .pagination a, #students-results nav a');
    if (!link || !link.href) return;
    e.preventDefault();
    fetchResults(link.href, true);
});

// Support the browser Back/Forward buttons
window.addEventListener('popstate', () => {
    fetchResults(window.location.href, false);
});

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

        // Attestation isn't decided at creation — it's always "no" until edited later
        document.getElementById('attestation-field')?.classList.add('hidden');
        document.getElementById('certificate-generated-field')?.classList.add('hidden');

    } else if (mode === 'edit' && student) {
        title.textContent = 'Modifier Candidat';
        form.action = `/students/${student.id}`;

        document.getElementById('attestation-field')?.classList.remove('hidden');
        document.getElementById('certificate-generated-field')?.classList.remove('hidden');

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
        form.querySelector('select[name="status"]').value = student.status || '';

        if (student.start_date) {
            form.querySelector('input[name="start_date"]').value = student.start_date.substring(0, 10);
        } else {
            form.querySelector('input[name="start_date"]').value = '';
        }

        form.querySelector('select[name="attestation"]').value = student.attestation || '';
        form.querySelector('select[name="certificate_generated"]').value = student.certificate_generated || 'no';
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
