  @extends('layouts.app')

  @section('title', 'Étudiants')

  @section('content')
  <div class="space-y-6">

      <!-- Header -->
      <div class="flex justify-between items-center">
          <div>
              <h2 class="text-3xl font-bold text-foreground">Étudiants</h2>
              <p class="text-muted-foreground mt-1">Gérez les étudiants de votre académie</p>
          </div>
          
          <button onclick="openModal('add')"
                  class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary/90 
                        text-primary-foreground rounded-lg font-medium shadow-sm transition-all hover:shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                  stroke="currentColor" stroke-width="2">
                  <line x1="12" y1="5" x2="12" y2="19"/>
                  <line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
              Ajouter Étudiant
          </button>
      </div>

      <!-- Filter Card -->
      <div class="bg-card border border-border rounded-xl shadow-sm p-5">
          <form method="GET" class="flex flex-wrap items-center gap-3">
              
              <!-- Search Input -->
              <div class="flex-1 min-w-[200px]">
                  <input type="text" name="search" placeholder="🔍 Rechercher par nom..."
                        value="{{ request('search') }}"
                        class="w-full border border-border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
              </div>

              <!-- Formation Filter -->
              <select name="formation_id" class="border border-border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all min-w-[200px]">
                  <option value="">📚 Toutes les Formations</option>
                  @foreach($formations as $formation)
                  <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                      {{ $formation->name }}
                  </option>
                  @endforeach
              </select>

              <!-- Filter Button -->
              <button type="submit" class="px-5 py-2.5 bg-primary text-primary-foreground rounded-lg font-medium hover:bg-primary/90 transition-all shadow-sm hover:shadow-md">
                  Filtrer
              </button>

              <!-- Export PDF -->
              <button formaction="{{ route('students.exportPdf') }}" formmethod="GET"
                      class="px-5 py-2.5 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-all shadow-sm hover:shadow-md">
                  📄 Exporter PDF
              </button>

          </form>
      </div>

      <!-- Students Table Card -->
      <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

          <div class="overflow-x-auto">
              <table class="w-full">
                  <thead class="bg-muted/50 border-b border-border">
                  <tr>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Nom</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">CIN</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Contact</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Formation</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Date Début</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Statut</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Attestation</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Paiement</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Ville</th>
                      <th class="text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Notes</th>
                      <th class="text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider py-4 px-4">Actions</th>
                  </tr>
                  </thead>

                  <tbody class="divide-y divide-border">
                  @foreach($students as $student)
                  <tr class="hover:bg-muted/30 transition-colors">

                      <!-- Name with Avatar -->
                      <td class="py-4 px-4">
                          <div class="flex items-center gap-3">
                              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center font-semibold text-primary">
                                  {{ strtoupper(substr($student->name, 0, 1)) }}
                              </div>
                              <div>
                                  <p class="font-semibold text-foreground text-sm">{{ $student->name }}</p>
                              </div>
                          </div>
                      </td>

                      <!-- CIN -->
                      <td class="py-4 px-4">
                          <p class="text-sm text-muted-foreground font-mono">{{ $student->cin }}</p>
                      </td>

                      <!-- Contact Info -->
                      <td class="py-4 px-4">
                          <div class="space-y-1">
                              <p class="text-sm text-foreground flex items-center gap-1.5">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                  </svg>
                                  {{ $student->phone }}
                              </p>
                              @if($student->email)
                              <p class="text-xs text-muted-foreground flex items-center gap-1.5">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                  </svg>
                                  {{ $student->email }}
                              </p>
                              @endif
                          </div>
                      </td>

                      <!-- Formation -->
                      <td class="py-4 px-4">
                          <p class="text-sm font-medium text-foreground">{{ $student->formation->name ?? '-' }}</p>
                      </td>

                      <!-- Start Date -->
                      <td class="py-4 px-4">
                          <p class="text-sm text-muted-foreground">{{ \Carbon\Carbon::parse($student->start_date)->format('d/m/Y') }}</p>
                      </td>
                      
                      <!-- Status Badge -->
                      <td class="py-4 px-4">
                          @php
                              $statusColors = [
                                  'aide_vendeur' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/30',
                                  'vendeur' => 'bg-green-50 text-green-700 ring-1 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/30',
                                  'superviseur' => 'bg-purple-50 text-purple-700 ring-1 ring-purple-600/20 dark:bg-purple-500/10 dark:text-purple-400 dark:ring-purple-500/30',
                                  'CDR' => 'bg-orange-50 text-orange-700 ring-1 ring-orange-600/20 dark:bg-orange-500/10 dark:text-orange-400 dark:ring-orange-500/30',
                              ];
                              $statusLabels = [
                                  'aide_vendeur' => 'Aide Vendeur',
                                  'vendeur' => 'Vendeur',
                                  'superviseur' => 'Superviseur',
                                  'CDR' => 'CDR',
                              ];
                          @endphp
                          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$student->status] ?? 'bg-gray-50 text-gray-700 ring-1 ring-gray-600/20' }}">
                              {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                          </span>
                      </td>
                      
                      <!-- Attestation Badge -->
      <td class="py-4 px-4">
                        @if($student->attestation === 'yes')
                            <span style="background-color: #22c55e !important; color: white !important;" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold">
                                Oui
                            </span>
                        @else
                            <span style="background-color: #ef4444 !important; color: white !important;" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold">
                                Non
                            </span>
                        @endif
                    </td>
                      <!-- Payment Info -->
                      <td class="py-4 px-4">
                          <div class="space-y-1">
                              <div class="flex items-center gap-2">
                                  <span class="text-xs text-muted-foreground">Payé:</span>
                                  <span class="text-sm font-semibold text-green-600">{{ number_format($student->payment_done, 0) }} DH</span>
                              </div>
                              <div class="flex items-center gap-2">
                                  <span class="text-xs text-muted-foreground">Reste:</span>
                                  <span class="text-sm font-semibold text-orange-600">{{ number_format($student->payment_remaining, 0) }} DH</span>
                              </div>
                          </div>
                      </td>
                      
                      <!-- City -->
                      <td class="py-4 px-4">
                          <p class="text-sm text-muted-foreground">{{ $student->city ?? '-' }}</p>
                      </td>

                      <!-- Notes -->
                      <td class="py-4 px-4">
                          @if($student->notes)
                              <div class="max-w-[150px] truncate text-sm text-muted-foreground" title="{{ $student->notes }}">
                                  {{ $student->notes }}
                              </div>
                          @else
                              <span class="text-sm text-muted-foreground">-</span>
                          @endif
                      </td>

                      <!-- Actions -->
                      <td class="py-4 px-4">
                          <div class="flex justify-end gap-2">
                              <!-- Edit Button -->
                              <button onclick="openModal('edit', {{ $student }})" 
                                      class="p-2 rounded-lg hover:bg-primary/10 text-muted-foreground hover:text-primary transition-all"
                                      title="Modifier">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                  </svg>
                              </button>

                              <!-- Delete Button -->
                              <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')" 
                                          class="p-2 rounded-lg hover:bg-red-50 text-muted-foreground hover:text-red-600 transition-all"
                                          title="Supprimer">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-muted/50 mb-4">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                          </svg>
                      </div>
                      <h3 class="text-lg font-semibold text-foreground mb-1">Aucun étudiant trouvé</h3>
                      <p class="text-sm text-muted-foreground">Commencez par ajouter votre premier étudiant</p>
                  </div>
              @endif

          </div>

          <!-- Pagination -->
          @if($students->hasPages())
          <div class="border-t border-border px-6 py-4 bg-muted/20">
              {{ $students->withQueryString()->links() }}
          </div>
          @endif

      </div>
  </div>

  <!-- Modal -->
  <div id="student-modal" class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex justify-center items-center p-4 z-50">
    <div class="bg-white border border-gray-300 rounded-xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh]">

      <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-primary/5 to-transparent rounded-t-xl flex-shrink-0">
        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Ajouter Étudiant</h3>
        <button onclick="closeStudentModal()" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="overflow-y-auto p-6 bg-gray-50 flex-1" style="min-height: 0;">
        <form method="POST" action="{{ route('students.store') }}" id="student-form" class="grid grid-cols-2 gap-4">
          @csrf
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom *</label>
            <input type="text" name="name" placeholder="Nom complet" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">CIN *</label>
            <input type="text" name="cin" placeholder="CIN" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone *</label>
            <input type="text" name="phone" placeholder="Téléphone" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail</label>
            <input type="email" name="email" placeholder="E-mail" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Formation *</label>
            <select name="formation_id" id="formation_id" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
              <option value="">Sélectionner</option>
              @foreach(App\Models\Formation::all() as $formation)
              <option value="{{ $formation->id }}" data-price="{{ $formation->price }}">
                {{ $formation->name }} - {{ number_format($formation->price, 0) }} DH
              </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date de Début *</label>
            <input type="date" name="start_date" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Statut *</label>
            <select name="status" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
              <option value="aide_vendeur">Aide Vendeur</option>
              <option value="vendeur">Vendeur</option>
              <option value="superviseur">Superviseur</option>
              <option value="CDR">CDR</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Attestation *</label>
            <select name="attestation" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
              <option value="yes">Oui</option>
              <option value="no">Non</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Payé</label>
            <input type="number" step="1" name="payment_done" id="payment_done" placeholder="0" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Reste</label>
            <input type="number" step="1" name="payment_remaining" id="payment_remaining" value="0" readonly class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ville</label>
            <input type="text" name="city" placeholder="Ville" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
            <textarea name="notes" placeholder="Notes..." class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm h-20 resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"></textarea>
          </div>
        </form>
      </div>

      <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-white rounded-b-xl flex-shrink-0">
        <button type="button" onclick="closeStudentModal()" class="px-5 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition-all text-sm font-medium">
          Annuler
        </button>
        <button type="submit" form="student-form" class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all font-semibold text-sm shadow-sm hover:shadow-md">
          💾 Enregistrer
        </button>
      </div>

    </div>
  </div>

  <script>
  function openModal(mode, student = null) {
      const modal = document.getElementById('student-modal');
      if (!modal) return;
      modal.classList.remove('hidden');
      const form = document.getElementById('student-form');
      const title = document.getElementById('modal-title');
      
      if (mode === 'add') {
          title.textContent = 'Ajouter Étudiant';
          form.action = '{{ route("students.store") }}';
          form.reset();
          let methodInput = form.querySelector('input[name="_method"]');
          if (methodInput) methodInput.remove();
      } else if (mode === 'edit' && student) {
          title.textContent = 'Modifier Étudiant';
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
          form.querySelector('input[name="start_date"]').value = student.start_date || '';
          form.querySelector('select[name="status"]').value = student.status || '';
          form.querySelector('select[name="attestation"]').value = student.attestation || '';
          form.querySelector('input[name="payment_done"]').value = Math.round(student.payment_done) || '';
          form.querySelector('input[name="payment_remaining"]').value = Math.round(student.payment_remaining) || '';
          form.querySelector('input[name="city"]').value = student.city || '';
          form.querySelector('textarea[name="notes"]').value = student.notes || '';
      }
  }

  function closeStudentModal() {
      const modal = document.getElementById('student-modal');
      if (modal) {
          modal.classList.add('hidden');
          document.getElementById('student-form').reset();
      }
  }

  document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('student-modal');
      if (modal) {
          modal.addEventListener('click', function(e) {
              if (e.target === modal) closeStudentModal();
          });
      }
      
      const formationSelect = document.getElementById('formation_id');
      const paymentDoneInput = document.getElementById('payment_done');
      const paymentRemainingInput = document.getElementById('payment_remaining');
      
      function calculateRemaining() {
          const selectedOption = formationSelect.options[formationSelect.selectedIndex];
          const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
          const paymentDone = parseFloat(paymentDoneInput.value) || 0;
          const remaining = price - paymentDone;
          paymentRemainingInput.value = remaining >= 0 ? Math.round(remaining) : 0;
      }
      
      if (formationSelect && paymentDoneInput) {
          formationSelect.addEventListener('change', calculateRemaining);
          paymentDoneInput.addEventListener('input', calculateRemaining);
      }
  });

  document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeStudentModal();
  });



  </script>

  @endsection