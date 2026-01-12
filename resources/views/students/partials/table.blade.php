@forelse($students as $student)
<tr class="border-b border-border hover:bg-muted/50 transition-colors">
    <td class="px-6 py-4">
        <div class="font-medium text-foreground">{{ $student->name }}</div>
        <div class="text-sm text-muted-foreground">{{ $student->cin }}</div>
    </td>
    <td class="px-6 py-4 text-sm text-muted-foreground">{{ $student->email }}</td>
    <td class="px-6 py-4 text-sm text-muted-foreground">{{ $student->phone }}</td>
    <td class="px-6 py-4 text-sm text-muted-foreground">{{ $student->city }}</td>
    <td class="px-6 py-4 text-sm">
        <span class="px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
            {{ $student->formation->name ?? 'N/A' }}
        </span>
    </td>
    <td class="px-6 py-4">
        @if($student->payment_remaining <= 0)
            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                Payé
            </span>
        @else
            <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                {{ number_format($student->payment_remaining, 2) }} DH
            </span>
        @endif
    </td>
    <td class="px-6 py-4 text-right">
        <button onclick="openModal('edit', {{ $student->id }})" 
                class="text-primary hover:text-primary/80 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
        <svg class="mx-auto h-12 w-12 text-muted-foreground/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p class="mt-4 font-medium">Aucun étudiant trouvé</p>
        <p class="mt-1 text-sm">Essayez de modifier vos filtres</p>
    </td>
</tr>
@endforelse
