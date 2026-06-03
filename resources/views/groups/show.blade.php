<x-app-layout>
<div class="p-6 max-w-4xl mx-auto">
  <h1 class="text-2xl font-bold">{{ $group->name }}</h1>
  <p class="text-gray-600">Mois: {{ optional($group->month_date)->format('m/Y') }}</p>
  <p>{{ $group->notes }}</p>
  <h2 class="text-xl font-semibold mt-6 mb-2">Candidats ({{ $group->students->count() }})</h2>
  <table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-100"><tr><th class="p-2">Nom</th><th>CIN</th><th>Tél</th><th>Formation</th></tr></thead>
    <tbody>
    @foreach($group->students as $s)
      <tr class="border-t"><td class="p-2">{{ $s->name }}</td><td class="text-center">{{ $s->cin }}</td><td class="text-center">{{ $s->phone }}</td><td class="text-center">{{ optional($s->formation)->name }}</td></tr>
    @endforeach
    </tbody>
  </table>
</div>
</x-app-layout>
