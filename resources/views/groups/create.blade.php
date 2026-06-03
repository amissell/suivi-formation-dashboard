<x-app-layout>
<div class="p-6 max-w-3xl mx-auto">
  <h1 class="text-2xl font-bold mb-4">Nouveau groupe</h1>
  <form method="POST" action="{{ route('groups.store') }}" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    <div>
      <label>Nom du groupe</label>
      <input name="name" required class="w-full border rounded p-2" placeholder="Groupe Janvier 2026">
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label>Mois</label>
        <input type="date" name="month_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label>Formation</label>
        <select name="formation_id" class="w-full border rounded p-2">
          <option value="">—</option>
          @foreach($formations as $f)<option value="{{ $f->id }}">{{ $f->name }}</option>@endforeach
        </select>
      </div>
    </div>
    <div>
      <label>Candidats</label>
      <input type="text" id="searchStudents" placeholder="Rechercher..." class="w-full border rounded p-2 mb-2">
      <div class="border rounded p-2 h-64 overflow-y-auto" id="studentList">
        @foreach($students as $s)
          <label class="flex items-center gap-2 p-1 hover:bg-gray-50 student-row" data-name="{{ strtolower($s->name) }}">
            <input type="checkbox" name="students[]" value="{{ $s->id }}">
            <span>{{ $s->name }} <small class="text-gray-500">— {{ optional($s->formation)->name }}</small></span>
          </label>
        @endforeach
      </div>
    </div>
    <textarea name="notes" placeholder="Notes" class="w-full border rounded p-2"></textarea>
    <button class="bg-blue-600 text-white px-6 py-2 rounded">Créer</button>
  </form>
</div>
<script>
document.getElementById('searchStudents').addEventListener('input', e => {
  const v = e.target.value.toLowerCase();
  document.querySelectorAll('.student-row').forEach(r =>
    r.style.display = r.dataset.name.includes(v) ? '' : 'none');
});
</script>
</x-app-layout>
