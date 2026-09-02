<div id="students-results" class="space-y-6">
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
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Groupes</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Engagement</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Attestation</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Certificat Généré</th>
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

                        <!-- Groups -->
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1 max-w-[180px]">
                                @forelse($student->groups as $g)
                                    <a href="{{ route('groups.show', $g) }}"
                                       title="{{ optional($g->formation)->name ?? '' }}"
                                       class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                        {{ $g->name }}
                                    </a>
                                @empty
                                    <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Engagement (attendance-based, auto-calculated) -->
                        <td class="py-3 px-4">
                            @php $rate = $student->engagement_rate; @endphp
                            @if($rate === null)
                                <span class="text-xs text-gray-400 dark:text-gray-500">Aucune séance</span>
                            @else
                                @php
                                    $barColor = $rate >= 75 ? 'bg-green-500' : ($rate >= 50 ? 'bg-amber-500' : 'bg-red-500');
                                    $badgeColor = $rate >= 75
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                        : ($rate >= 50
                                            ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                                            : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300');
                                @endphp
                                <div class="space-y-1 min-w-[120px]">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                        <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ $rate }}%"></div>
                                    </div>
                                    <span class="inline-block px-1.5 py-0.5 rounded text-xs font-semibold {{ $badgeColor }}">
                                        {{ $rate }}%
                                    </span>
                                </div>
                            @endif
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

                        <!-- Certificate Generated Status -->
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1">
                                @if($student->certificate_generated === 'yes')
                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-xs text-green-600 font-medium">Oui</span>
                                @else
                                    <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.59c.75 1.334-.213 2.987-1.743 2.987H3.482c-1.53 0-2.493-1.653-1.743-2.987l6.518-11.59zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xs text-amber-600 font-medium">Non</span>
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
