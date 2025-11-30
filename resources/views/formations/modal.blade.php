<!-- formations/modal.blade.php -->
<div id="formation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-card p-6 rounded-lg w-96 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-foreground">&times;</button>
        <h2 class="text-xl font-bold mb-4" id="modal-title">Add Formation</h2>
        <form id="formation-form" method="POST" action="/formations">
            @csrf
            <div class="mb-3">
                <label for="name" class="block text-sm font-medium text-foreground">Name</label>
                <input type="text" id="name" name="name" class="w-full border border-input rounded-md p-2" required>
            </div>
            <div class="mb-3">
                <label for="trainer" class="block text-sm font-medium text-foreground">Trainer</label>
                <input type="text" id="trainer" name="trainer" class="w-full border border-input rounded-md p-2" required>
            </div>
            <div class="mb-3">
                <label for="description" class="block text-sm font-medium text-foreground">Description</label>
                <textarea id="description" name="description" class="w-full border border-input rounded-md p-2"></textarea>
            </div>
            <div class="mb-3">
                <label for="duration" class="block text-sm font-medium text-foreground">Duration</label>
                <input type="text" id="duration" name="duration" class="w-full border border-input rounded-md p-2">
            </div>
            <div class="mb-3">
                <label for="enrolled" class="block text-sm font-medium text-foreground">Enrolled</label>
                <input type="number" id="enrolled" name="enrolled" class="w-full border border-input rounded-md p-2">
            </div>
            <div class="mb-3">
                <label for="capacity" class="block text-sm font-medium text-foreground">Capacity</label>
                <input type="number" id="capacity" name="capacity" class="w-full border border-input rounded-md p-2">
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-muted hover:bg-muted/80 rounded-md mr-2">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
function openModal(mode, formation = null) {
    const modal = document.getElementById('formation-modal');
    const form = document.getElementById('formation-form');
    const title = document.getElementById('modal-title');
    const methodInput = document.getElementById('form-method');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    if(mode === 'edit' && formation) {
        title.textContent = 'Edit Formation';
        form.action = `/formations/${formation.id}`;
        methodInput.value = 'PUT';

        document.getElementById('name').value = formation.name;
        document.getElementById('trainer').value = formation.trainer;
        document.getElementById('description').value = formation.description || '';
        document.getElementById('duration').value = formation.duration || '';
        document.getElementById('capacity').value = formation.capacity || '';
    } else {
        title.textContent = 'Add Formation';
        form.action = "{{ route('formations.store') }}";
        methodInput.value = 'POST';

        form.reset();
    }
}

function closeModal() {
    const modal = document.getElementById('formation-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
