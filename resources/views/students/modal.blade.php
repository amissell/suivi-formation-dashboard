<div id="student-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 hidden">
    <div class="bg-card rounded-lg w-96 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="modal-title text-lg font-semibold text-foreground">Add Student</h3>
            <button onclick="closeModal()" class="text-foreground">&times;</button>
        </div>
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div class="space-y-3">
                <input type="text" name="name" placeholder="Name" class="w-full border rounded p-2" required>
                <input type="text" name="cin" placeholder="CIN" class="w-full border rounded p-2" required>
                <input type="text" name="phone" placeholder="Phone" class="w-full border rounded p-2" required>
                <input type="email" name="email" placeholder="Email" class="w-full border rounded p-2">
                <select name="formation_id" class="w-full border rounded p-2" required>
                    <option value="">Select Formation</option>
                    @foreach(App\Models\Formation::all() as $formation)
                        <option value="{{ $formation->id }}">{{ $formation->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="w-full border rounded p-2" required>
                    <option value="aide_vendeur">Aide Vendeur</option>
                    <option value="vendeur">Vendeur</option>
                    <option value="superviseur">Superviseur</option>
                    <option value="CDR">CDR</option>
                </select>
                {{-- <input type="date" name="start_date" class="w-full border rounded p-2" required> --}}
                <td class="py-4 text-foreground">{{ \Carbon\Carbon::parse($student->start_date)->format('Y-m-d') }}</td>

            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded">Save</button>
            </div>
        </form>
    </div>
</div>
