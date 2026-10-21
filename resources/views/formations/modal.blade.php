<div id="formation-modal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/55 p-4 backdrop-blur-sm" onclick="if(event.target === this) closeFormationModal()">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-border bg-card shadow-2xl">
        <div class="flex items-start justify-between border-b border-border p-5 sm:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[.16em] text-primary">Catalogue pédagogique</p>
                <h2 class="mt-1 text-xl font-bold text-foreground" id="formation-modal-title">Ajouter une formation</h2>
                <p class="mt-1 text-sm text-muted-foreground">Renseignez les informations principales.</p>
            </div>
            <button type="button" onclick="closeFormationModal()" aria-label="Fermer" class="rounded-lg p-2 text-muted-foreground transition hover:bg-muted hover:text-foreground">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 6 12 12M18 6 6 18" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>

        <form id="formation-form" method="POST" action="{{ route('formations.store') }}" class="space-y-5 p-5 sm:p-6">
            @csrf
            <input type="hidden" name="_method" id="formation-method" value="POST">
            <div>
                <label for="formation-name" class="mb-1.5 block text-sm font-medium text-foreground">Nom de la formation <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="formation-name" required maxlength="255" placeholder="Ex. Vente et relation client" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label for="formation-trainer" class="mb-1.5 block text-sm font-medium text-foreground">Formateur <span class="text-red-500">*</span></label>
                <input type="text" name="trainer" id="formation-trainer" required maxlength="255" placeholder="Nom du formateur" class="w-full rounded-lg border border-border bg-background px-3.5 py-2.5 text-sm text-foreground placeholder:text-muted-foreground/70 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="flex flex-col-reverse gap-2 border-t border-border pt-4 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeFormationModal()" class="rounded-lg border border-border px-4 py-2.5 text-sm font-semibold text-foreground transition hover:bg-muted">Annuler</button>
                <button type="submit" id="formation-submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:bg-primary/90">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openFormationModal(mode, formation = null) {
    const modal = document.getElementById('formation-modal');
    const form = document.getElementById('formation-form');
    const isEdit = mode === 'edit' && formation;

    form.reset();
    document.getElementById('formation-modal-title').textContent = isEdit ? 'Modifier la formation' : 'Ajouter une formation';
    document.getElementById('formation-submit').textContent = isEdit ? 'Enregistrer les modifications' : 'Créer la formation';
    form.action = isEdit ? `/formations/${formation.id}` : @json(route('formations.store'));
    document.getElementById('formation-method').value = isEdit ? 'PUT' : 'POST';

    if (isEdit) {
        document.getElementById('formation-name').value = formation.name || '';
        document.getElementById('formation-trainer').value = formation.trainer || '';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    document.getElementById('formation-name').focus();
}

function closeFormationModal() {
    const modal = document.getElementById('formation-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.addEventListener('keydown', event => {
    if (event.key === 'Escape') closeFormationModal();
});
</script>
@endpush
