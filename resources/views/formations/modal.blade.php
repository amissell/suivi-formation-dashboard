<!-- formations/modal.blade.php -->
<div id="formation-modal" class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm flex justify-center items-center">
    <div class="bg-card p-6 rounded-xl w-full max-w-md relative shadow-lg border border-border">
        
        <!-- Close button -->
        <button onclick="closeFormationModal()" 
                class="absolute top-3 right-3 text-2xl leading-none hover:text-red-500">
            &times;
        </button>

        <h2 class="text-xl font-bold mb-5" id="formation-modal-title">Add Formation</h2>

        <form id="formation-form" method="POST" action="{{ route('formations.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formation-method" value="POST">

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" id="formation-name"
                       class="w-full border border-input rounded-md p-2" required>
            </div>

            <!-- Trainer -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Trainer</label>
                <input type="text" name="trainer" id="formation-trainer"
                       class="w-full border border-input rounded-md p-2" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" id="formation-description"
                          class="w-full border border-input rounded-md p-2"></textarea>
            </div>
            
            
            <!-- <div class="mb-3">
                <label for="price" class="block text-sm font-medium text-foreground">Price (DH)</label>
            <input type="number" id="price" name="price" class="w-full border border-input rounded-md p-2" step="0.01" required>
        </div> -->



            <!-- Footer -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeFormationModal()"
                        class="px-4 py-2 bg-muted hover:bg-muted/80 rounded-md">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded-md">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function openFormationModal(mode, formation = null) {
    const modal = document.getElementById('formation-modal');
    const title = document.getElementById('formation-modal-title');
    const form = document.getElementById('formation-form');
    const method = document.getElementById('formation-method');

    // Reset form before applying values
    form.reset();

    // Open modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    if (mode === 'edit' && formation) {
        title.textContent = "Edit Formation";
        form.action = `/formations/${formation.id}`;
        method.value = "PUT";

        // Fill inputs
        document.getElementById('formation-name').value = formation.name;
        document.getElementById('formation-trainer').value = formation.trainer;
        document.getElementById('formation-description').value = formation.description || '';
        document.getElementById('price').value = formation.price || '';
    } 
    else {
        title.textContent = "Add Formation";
        form.action = "{{ route('formations.store') }}";
        method.value = "POST";
    }
}

function closeFormationModal() {
    const modal = document.getElementById('formation-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
