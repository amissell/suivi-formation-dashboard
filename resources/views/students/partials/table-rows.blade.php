@forelse($students as $student)
<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
    <td class="py-3 px-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center font-semibold text-sm text-blue-600 dark:text-blue-400">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ $student->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->cin }}</p>
            </div>
        </div>
    </td>
    <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $student->email }}</td>
    <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $student->phone }}</td>
    <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $student->formation->name ?? '-' }}</td>
    <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $student->city }}</td>
    <td class="py-3 px-4">
        @if($student->payment_remaining <= 0)
            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Payé</span>
        @else
            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">{{ number_format($student->payment_remaining, 2) }} DH</span>
        @endif
    </td>
    <td class="py-3 px-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('students.edit', $student) }}" 
               class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="py-12 text-center text-gray-500 dark:text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p class="font-medium">Aucun étudiant trouvé</p>
        <p class="text-sm">Essayez de modifier vos filtres</p>
    </td>
</tr>
@endforelse
