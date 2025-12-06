<div id="student-modal" class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex justify-center items-center">
    <div class="bg-card border border-border rounded-lg w-full max-w-lg p-6 shadow-lg">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="modal-title text-xl font-semibold text-foreground">Ajouter Étudiant</h3>
            <button onclick="closeModal()" class="text-foreground text-2xl leading-none">&times;</button>
        </div>

        <form method="POST" action="{{ route('students.store') }}" id="student-form">
            @csrf

            <div class="space-y-4">

                <input type="text" name="name" placeholder="Nom"
                       class="w-full border border-border bg-background rounded-md p-2" required>

                <input type="text" name="cin" placeholder="CIN"
                       class="w-full border border-border bg-background rounded-md p-2" required>

                <input type="text" name="phone" placeholder="Téléphone"
                       class="w-full border border-border bg-background rounded-md p-2" required>

                <input type="email" name="email" placeholder="E-mail"
                       class="w-full border border-border bg-background rounded-md p-2">

                <select name="formation_id" id="formation_id" class="w-full border rounded p-2" required>
                    <option value="">Sélectionner une Formation</option>
                    @foreach(App\Models\Formation::all() as $formation)
                    <option value="{{ $formation->id }}" data-price="{{ $formation->price }}">
                        {{ $formation->name }} - {{ $formation->price }} DH
                    </option>
                    @endforeach
                </select>


                <input type="date" name="start_date"
                       class="w-full border border-border bg-background rounded-md p-2" required>

                <select name="status"
                        class="w-full border border-border bg-background rounded-md p-2" required>
                    <option value="aide_vendeur">Aide Vendeur</option>
                    <option value="vendeur">Vendeur</option>
                    <option value="superviseur">Superviseur</option>
                    <option value="CDR">CDR</option>
                </select>

                <select name="attestation"
                        class="w-full border border-border bg-background rounded-md p-2" required>
                    <option value="yes">Attestation : Oui</option>
                    <option value="no">Attestation : Non</option>
                </select>

                <input type="number" step="0.01" name="payment_done" id="payment_done" placeholder="Paiement Effectué" class="w-full border rounded p-2">
                <input type="number" step="0.01" name="payment_remaining" id="payment_remaining" value="0" readonly class="w-full border rounded p-2 mt-2">




                <input type="text" name="city" placeholder="Ville"
                       class="w-full border border-border bg-background rounded-md p-2">

                <textarea name="notes" placeholder="Notes"
                          class="w-full border border-border bg-background rounded-md p-2 h-20"></textarea>

            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-border rounded-md bg-background hover:bg-muted">
                    Annuler
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
