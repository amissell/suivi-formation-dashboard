<x-app-layout>
<div class="p-6">
  <div class="flex justify-between mb-4">
    <h1 class="text-2xl font-bold">Groupes</h1>
    <a href="{{ route('groups.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nouveau groupe</a>
  </div>

  <form method="GET" class="flex gap-2 mb-4">
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded p-2">
    <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="border rounded p-2">
    <button class="bg-gray-700 text-white px-4 rounded">Filtrer</button>
    <a href="{{ route('groups.index') }}" class="px-4 py-2">Reset</a>
  </form>

  <table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-100">
      <tr><th class="p-2 text-left">Nom</th><th>Mois</th><th>Formation</th><th>Candidats</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($groups as $g)
      <tr class="border-t">
        <td class="p-2">{{ $g->name }}</td>
        <td class="text-center">{{ optional($g->month_date)->format('m/Y') }}</td>
        <td class="text-center">{{ optional($g->formation)->name }}</td>
        <td class="text-center">{{ $g->students->count() }}</td>
        <td class="text-right p-2">
          <a href="{{ route('groups.show',$g) }}" class="text-blue-600">Voir</a>
          <a href="{{ route('groups.edit',$g) }}" class="text-yellow-600 ml-2">Modifier</a>
          <form action="{{ route('groups.destroy',$g) }}" method="POST" class="inline" onsubmit="return confirm('Sûr?')">
            @csrf @method('DELETE')
            <button class="text-red-600 ml-2">Suppr</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="mt-4">{{ $groups->links() }}</div>
</div>
</x-app-layout>
