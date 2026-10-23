<div id="students-results" class="space-y-6">
    <!-- Results Count -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-muted-foreground">
            <span id="results-count" class="font-medium text-foreground">{{ $students->total() }}</span> candidat(s) trouvé(s)
        </p>
    </div>

    {{-- Compact cards on phones and tablets --}}
    <div class="space-y-3 lg:hidden">
        @forelse($students as $student)
            @php
                $engagement = (float) ($student->engagement ?? 0);
                $paid = (float) ($student->payment_done ?? 0);
                $remaining = (float) ($student->payment_remaining ?? 0);
                $paymentProgress = $engagement > 0 ? min(100, ($paid / $engagement) * 100) : 0;
                $rate = $student->engagement_rate;
            @endphp
            <article class="rounded-xl border border-border bg-card p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-primary dark:bg-blue-400/10">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate font-semibold text-foreground">{{ $student->name }}</h3>
                        <p class="text-xs font-mono text-muted-foreground">{{ $student->cin }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-1">
                        <a href="{{ route('students.edit', $student) }}" class="rounded-lg p-2 text-muted-foreground transition hover:bg-muted hover:text-primary" aria-label="Modifier {{ $student->name }}" title="Modifier">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m16 4 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        @if(filled($student->notes))
                            <button type="button" onclick='showNotes(@json($student->notes), @json($student->name))' class="rounded-lg p-2 text-muted-foreground transition hover:bg-muted hover:text-primary" aria-label="Voir les notes de {{ $student->name }}" title="Voir les notes">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </button>
                        @endif
                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Supprimer ce candidat ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg p-2 text-muted-foreground transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30" aria-label="Supprimer {{ $student->name }}" title="Supprimer">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16m-10 4v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                    <div class="min-w-0"><p class="text-[11px] font-medium uppercase tracking-wide text-muted-foreground">Téléphone</p><p class="mt-0.5 truncate text-foreground">{{ $student->phone ?: '—' }}</p></div>
                    <div class="min-w-0"><p class="text-[11px] font-medium uppercase tracking-wide text-muted-foreground">Formation</p><p class="mt-0.5 truncate text-foreground">{{ $student->formation->name ?? '—' }}</p></div>
                    <div><p class="text-[11px] font-medium uppercase tracking-wide text-muted-foreground">Présence</p><p class="mt-0.5 font-semibold text-foreground">{{ $rate === null ? 'Aucune séance' : $rate . '%' }}</p></div>
                    <div><p class="text-[11px] font-medium uppercase tracking-wide text-muted-foreground">Paiement restant</p><p class="mt-0.5 font-semibold {{ $remaining > 0 ? 'text-amber-600 dark:text-amber-300' : 'text-emerald-600 dark:text-emerald-300' }}">{{ number_format($remaining, 0) }} DH</p></div>
                </div>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @forelse($student->groups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="max-w-full truncate rounded-full bg-blue-50 px-2 py-1 text-[11px] font-medium text-blue-700 dark:bg-blue-400/10 dark:text-blue-200">{{ $group->name }}</a>
                    @empty
                        <span class="text-xs text-muted-foreground">Aucun groupe</span>
                    @endforelse
                    <span class="rounded-full bg-muted px-2 py-1 text-[11px] text-muted-foreground">Début : {{ optional($student->start_date)->format('d/m/Y') ?? '—' }}</span>
                    <span class="rounded-full bg-muted px-2 py-1 text-[11px] text-muted-foreground">Attestation : {{ $student->attestation === 'yes' ? 'Oui' : 'Non' }}</span>
                    <span class="rounded-full bg-muted px-2 py-1 text-[11px] text-muted-foreground">Certificat : {{ $student->certificate_generated === 'yes' ? 'Généré' : 'Non généré' }}</span>
                </div>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-muted"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $paymentProgress }}%"></div></div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-border bg-card px-5 py-12 text-center">
                <h3 class="font-semibold text-foreground">Aucun candidat trouvé</h3>
                <p class="mt-1 text-sm text-muted-foreground">Essayez de modifier les filtres ou ajoutez un candidat.</p>
            </div>
        @endforelse
    </div>

    {{-- Keep every candidate detail available in the desktop table; scroll horizontally on smaller screens. --}}
    <div id="students-table-container" class="hidden overflow-x-auto overflow-y-hidden rounded-xl border border-border bg-card lg:block" style="overflow-y: hidden; max-height: none; height: auto;">
        <table class="w-full min-w-[1500px] text-sm">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Candidat</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Contact</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Formation</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Profession / statut</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Groupes</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Engagement</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Attestation</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Certificat Généré</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Paiement</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Ville</th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table-body" class="divide-y divide-border" style="display: table-row-group; overflow: visible; max-height: none; height: auto;">
                    @foreach($students as $student)
                    <tr class="hover:bg-muted/50 transition-colors">
                        <!-- Student Info (Name + CIN + Avatar) -->
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 flex items-center justify-center font-semibold text-sm text-blue-600 dark:text-blue-400 shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-foreground truncate">
                                        {{ $student->name }}
                                    </p>
                                    <p class="text-xs text-muted-foreground font-mono">
                                        {{ $student->cin }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Contact Info -->
                        <td class="py-3 px-4">
                            <div class="space-y-1">
                                <p class="text-xs text-foreground flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $student->phone }}
                                </p>
                                @if($student->email)
                                <p class="text-xs text-muted-foreground flex items-center gap-1.5 truncate max-w-[180px]">
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
                            <p class="truncate text-sm font-medium text-foreground">{{ $student->formation->name ?? '-' }}</p>
                        </td>

                        <!-- Profession / Status -->
                        <td class="py-3 px-4">
                            <p class="text-sm text-foreground">{{ \App\Models\Student::PROFESSIONS[$student->status] ?? $student->status ?? '-' }}</p>
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
                                    <span class="text-xs text-muted-foreground">—</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Engagement (attendance-based, auto-calculated) -->
                        <td class="py-3 px-4">
                            @php $rate = $student->engagement_rate; @endphp
                            @if($rate === null)
                                <span class="text-xs text-muted-foreground">Aucune séance</span>
                            @else
                                @php
                                    $barColor = $rate >= 75 ? 'bg-green-500' : ($rate >= 50 ? 'bg-amber-500' : 'bg-red-500');
                                    $badgeColor = $rate >= 75
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                        : ($rate >= 50
                                            ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'
                                            : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300');
                                @endphp
                                <div class="w-full max-w-[140px] space-y-1">
                                    <div class="w-full bg-muted rounded-full h-1.5">
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
                            <p class="text-xs text-muted-foreground">
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
                            <div class="w-full min-w-0 space-y-2">
                                @php
                                    $engagement = $student->engagement ?? 0;
                                    $paid = $student->payment_done ?? 0;
                                    $remaining = $student->payment_remaining ?? 0;
                                    $percentage = $engagement > 0 ? ($paid / $engagement) * 100 : 0;
                                @endphp

                                <!-- Progress Bar -->
                                <div class="w-full bg-muted rounded-full h-1.5">
                                    <div class="bg-green-600 h-1.5 rounded-full transition-all"
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>

                                <!-- Payment Details -->
                                <div class="flex flex-wrap items-center justify-between gap-x-1 text-xs">
                                    <span class="text-green-600 font-semibold">
                                        {{ number_format($paid, 0) }} DH
                                    </span>
                                    <span class="text-muted-foreground">/</span>
                                    <span class="text-foreground font-medium">
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
                            <p class="text-xs text-muted-foreground">{{ $student->city ?? '-' }}</p>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 px-4">
                            <div class="flex gap-1">
                                @if(!is_null($student->notes) && trim($student->notes) !== '')
                                <button type="button"
                                        onclick='showNotes(@json($student->notes), @json($student->name))'
                                        class="p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-muted-foreground hover:text-blue-600 transition-colors"
                                        title="Voir notes">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586"/>
                                    </svg>
                                </button>
                                @endif

                                <!-- Edit Button -->
                                <a href="{{ route('students.edit', $student) }}"
                                        class="p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-muted-foreground hover:text-blue-600 transition-colors"
                                        title="Modifier">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ addslashes($student->name) }} ?')"
                                            class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-muted-foreground hover:text-red-600 transition-colors"
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
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-muted mb-4">
                    <svg class="w-8 h-8 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground mb-1">Aucun candidat trouvé</h3>
                <p class="text-sm text-muted-foreground">Commencez par ajouter votre premier candidat</p>
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
        <div id="pagination-container" class="p-4 border-t border-border">
            {{ $students->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
