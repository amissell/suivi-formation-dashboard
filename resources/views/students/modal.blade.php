<div id="student-modal" class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex justify-center items-start pt-10 overflow-y-auto z-50">
  <div class="bg-white border border-gray-300 rounded-lg w-full max-w-md shadow-2xl flex flex-col max-h-[90vh]">

    <!-- Header -->
    <div class="flex justify-between items-center p-4 border-b border-gray-300 flex-shrink-0">
      <h3 id="modal-title" class="text-lg font-semibold">Ajouter Étudiant</h3>
      <button type="button" onclick="closeStudentModal()" class="text-2xl text-gray-900 hover:text-blue-600">&times;</button>
    </div>

    <!-- Form Scrollable Content -->
    <div class="overflow-y-auto p-4 flex-1" style="min-height: 0;">
      <form method="POST" id="student-form" class="grid grid-cols-2 gap-3">
        @csrf
        <input type="hidden" name="student_id" id="student_id">

        <!-- Row 1 -->
        <div>
          <label class="block text-xs font-medium mb-1">Nom *</label>
          <input type="text" name="name" id="name" class="w-full border rounded p-2 text-sm" required>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">CIN *</label>
          <input type="text" name="cin" id="cin" class="w-full border rounded p-2 text-sm" required>
        </div>

        <!-- Row 2 -->
        <div>
          <label class="block text-xs font-medium mb-1">Téléphone *</label>
          <input type="text" name="phone" id="phone" class="w-full border rounded p-2 text-sm" required>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">E-mail</label>
          <input type="email" name="email" id="email" class="w-full border rounded p-2 text-sm">
        </div>

        <!-- Row 3 -->
        <div>
          <label class="block text-xs font-medium mb-1">Formation *</label>
          <select name="formation_id" id="formation_id" class="w-full border rounded p-2 text-sm" required>
            <option value="">Sélectionner</option>
            @foreach(App\Models\Formation::all() as $formation)
              <option value="{{ $formation->id }}" data-price="{{ $formation->price }}">{{ $formation->name }} - {{ $formation->price }} DH</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Date de Début *</label>
          <input type="date" name="start_date" id="start_date" class="w-full border rounded p-2 text-sm" required>
        </div>

        <!-- Row 4 -->
        <div>
          <label class="block text-xs font-medium mb-1">Statut *</label>
          <select name="status" id="status" class="w-full border rounded p-2 text-sm" required>
            <option value="aide_vendeur">Aide Vendeur</option>
            <option value="vendeur">Vendeur</option>
            <option value="superviseur">Superviseur</option>
            <option value="CDR">CDR</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Attestation *</label>
          <select name="attestation" id="attestation" class="w-full border rounded p-2 text-sm" required>
            <option value="yes">Oui</option>
            <option value="no">Non</option>
          </select>
        </div>

        <!-- Row 5 -->
        <div>
          <label class="block text-xs font-medium mb-1">Payé</label>
          <input type="number" step="0.01" name="payment_done" id="payment_done" class="w-full border rounded p-2 text-sm">
        </div>
        <div>
          <label class="block text-xs font-medium mb-1">Reste</label>
          <input type="number" step="0.01" name="payment_remaining" id="payment_remaining" readonly class="w-full border rounded p-2 bg-gray-100 text-sm cursor-not-allowed">
        </div>

        <!-- Row 6 -->
        <div>
          <label class="block text-xs font-medium mb-1">Ville</label>
          <input type="text" name="city" id="city" class="w-full border rounded p-2 text-sm">
        </div>
        <div></div> <!-- empty cell to maintain grid -->

        <!-- Row 7 (Notes full width) -->
        <div class="col-span-2">
          <label class="block text-xs font-medium mb-1">Notes</label>
          <textarea name="notes" id="notes" class="w-full border rounded p-2 text-sm h-20 resize-none"></textarea>
        </div>

      </form>
    </div>

    <!-- Footer -->
    <div class="flex justify-end space-x-3 p-4 border-t bg-white flex-shrink-0">
      <button type="button" onclick="closeStudentModal()" class="px-4 py-2 border rounded-md bg-white hover:bg-gray-100 text-sm">Annuler</button>
      <button type="submit" form="student-form" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">Enregistrer</button>
    </div>

  </div>
</div>
