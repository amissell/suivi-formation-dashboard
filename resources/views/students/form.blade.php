@if($errors->any())
    <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200">
        <p class="font-semibold">Vérifiez les informations saisies :</p>
        <ul class="mt-2 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ $editing ? route('students.update', $student) : route('students.store') }}" method="POST" class="space-y-5">
    @csrf
    @if($editing) @method('PUT') @endif

    <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
        <div class="mb-5 border-b border-border pb-4">
            <h3 class="font-semibold text-foreground">Informations personnelles</h3>
            <p class="mt-1 text-xs text-muted-foreground">Identité et coordonnées du candidat.</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-foreground">Nom complet <span class="text-red-500">*</span></label>
                <input id="name" name="name" value="{{ old('name', $student->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="cin" class="mb-1.5 block text-sm font-medium text-foreground">CIN <span class="text-red-500">*</span></label>
                <input id="cin" name="cin" value="{{ old('cin', $student->cin) }}" required maxlength="50" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('cin')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="mb-1.5 block text-sm font-medium text-foreground">Téléphone <span class="text-red-500">*</span></label>
                <input id="phone" name="phone" value="{{ old('phone', $student->phone) }}" required maxlength="20" autocomplete="tel" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-foreground">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email', $student->email) }}" maxlength="255" autocomplete="email" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="city" class="mb-1.5 block text-sm font-medium text-foreground">Ville</label>
                <input id="city" name="city" value="{{ old('city', $student->city) }}" maxlength="100" autocomplete="address-level2" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="start_date" class="mb-1.5 block text-sm font-medium text-foreground">Date de début <span class="text-red-500">*</span></label>
                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', optional($student->start_date)->format('Y-m-d')) }}" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('start_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
        <div class="mb-5 border-b border-border pb-4">
            <h3 class="font-semibold text-foreground">Formation et suivi</h3>
            <p class="mt-1 text-xs text-muted-foreground">Affectez le candidat à une formation.</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="formation_id" class="mb-1.5 block text-sm font-medium text-foreground">Formation <span class="text-red-500">*</span></label>
                <select id="formation_id" name="formation_id" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">Sélectionner une formation</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ (string)old('formation_id', $student->formation_id) === (string)$formation->id ? 'selected' : '' }}>{{ $formation->name }}</option>
                    @endforeach
                </select>
                @error('formation_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="attestation" class="mb-1.5 block text-sm font-medium text-foreground">Attestation</label>
                <select id="attestation" name="attestation" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="no" {{ old('attestation', $student->attestation ?? 'no') === 'no' ? 'selected' : '' }}>Non obtenue</option>
                    <option value="yes" {{ old('attestation', $student->attestation ?? 'no') === 'yes' ? 'selected' : '' }}>Obtenue</option>
                </select>
                @error('attestation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            @unless($editing)
                <div>
                    <label for="certificate_generated" class="mb-1.5 block text-sm font-medium text-foreground">Certificat généré</label>
                    <select id="certificate_generated" name="certificate_generated" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="no" {{ old('certificate_generated', 'no') === 'no' ? 'selected' : '' }}>Non</option>
                        <option value="yes" {{ old('certificate_generated', 'no') === 'yes' ? 'selected' : '' }}>Oui</option>
                    </select>
                    @error('certificate_generated')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            @endunless
            @if($editing)
                <div>
                    <label for="certificate_generated" class="mb-1.5 block text-sm font-medium text-foreground">Certificat généré</label>
                    <select id="certificate_generated" name="certificate_generated" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="no" {{ old('certificate_generated', $student->certificate_generated) === 'no' ? 'selected' : '' }}>Non</option>
                        <option value="yes" {{ old('certificate_generated', $student->certificate_generated) === 'yes' ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
            @endif
        </div>
    </section>

    <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
        <div class="mb-5 border-b border-border pb-4">
            <h3 class="font-semibold text-foreground">Informations de paiement</h3>
            <p class="mt-1 text-xs text-muted-foreground">Indiquez le montant convenu et la somme déjà réglée.</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label for="engagement" class="mb-1.5 block text-sm font-medium text-foreground">Montant total (DH) <span class="text-red-500">*</span></label>
                <input id="engagement" type="number" name="engagement" value="{{ old('engagement', $student->engagement) }}" min="0" step="0.01" required class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('engagement')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="payment_done" class="mb-1.5 block text-sm font-medium text-foreground">Déjà payé (DH)</label>
                <input id="payment_done" type="number" name="payment_done" value="{{ old('payment_done', $student->payment_done ?? 0) }}" min="0" step="0.01" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('payment_done')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end">
                <div class="w-full rounded-lg bg-muted/50 px-3.5 py-2.5">
                    <p class="text-xs text-muted-foreground">Solde estimé</p>
                    <p id="payment-remaining-preview" class="mt-0.5 font-semibold text-foreground">{{ number_format(max(0, (float)old('engagement', $student->engagement ?? 0) - (float)old('payment_done', $student->payment_done ?? 0)), 2) }} DH</p>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <label for="notes" class="mb-1.5 block text-sm font-medium text-foreground">Notes internes</label>
            <textarea id="notes" name="notes" rows="3" class="w-full resize-y rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('notes', $student->notes) }}</textarea>
        </div>
    </section>

    <div class="flex flex-col-reverse gap-2 border-t border-border pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('students.index') }}" class="rounded-lg border border-border px-4 py-2.5 text-center text-sm font-semibold text-foreground transition hover:bg-muted">Annuler</a>
        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:bg-primary/90">{{ $editing ? 'Enregistrer les modifications' : 'Créer le dossier' }}</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const total = document.getElementById('engagement');
    const paid = document.getElementById('payment_done');
    const preview = document.getElementById('payment-remaining-preview');
    const updatePreview = () => {
        if (!total || !paid || !preview) return;
        const remaining = Math.max(0, (parseFloat(total.value) || 0) - (parseFloat(paid.value) || 0));
        preview.textContent = remaining.toLocaleString('fr-MA', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' DH';
    };
    total?.addEventListener('input', updatePreview);
    paid?.addEventListener('input', updatePreview);
});
</script>
