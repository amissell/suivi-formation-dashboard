<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 -translate-x-full flex-col border-r border-sidebar-border bg-sidebar text-sidebar-foreground shadow-xl transition-all duration-300 ease-in-out lg:translate-x-0" data-collapsed="false">
    <a href="{{ route('dashboard') }}" class="group flex h-[76px] shrink-0 items-center gap-3 border-b border-sidebar-border px-5 transition-colors hover:bg-white/[.03]" aria-label="Must Négoce Academy — Dashboard">
        <img id="logo-full" src="{{ asset('images/logo.jpg') }}" alt="" class="h-10 w-10 rounded-xl bg-white object-contain p-0.5 shadow-sm">
        <span id="logo-mini" class="hidden h-10 w-10 items-center justify-center rounded-xl bg-amber-400 text-sm font-black tracking-tight text-slate-950">MN</span>
        <span class="logo-copy min-w-0">
            <span class="block truncate text-sm font-bold tracking-wide">MUST-NÉGOCE</span>
            <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[.2em] text-sidebar-foreground/55">Academy · Admin</span>
        </span>
    </a>

    <nav class="flex-1 overflow-y-auto px-3 py-6" aria-label="Navigation principale">
        <p id="nav-label" class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[.18em] text-sidebar-foreground/45">Menu principal</p>
        <ul class="space-y-1.5">
            @php
                $navigation = [
                    ['route' => 'dashboard', 'pattern' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                    ['route' => 'formations.index', 'pattern' => 'formations.*', 'label' => 'Formations', 'icon' => 'formations'],
                    ['route' => 'students.index', 'pattern' => 'students.*', 'label' => 'Candidats', 'icon' => 'students'],
                    ['route' => 'groups.index', 'pattern' => 'groups.*', 'label' => 'Groupes', 'icon' => 'groups'],
                ];
            @endphp
            @foreach($navigation as $item)
                @php $active = request()->routeIs($item['pattern']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}" aria-current="{{ $active ? 'page' : 'false' }}"
                       class="nav-item relative flex h-11 items-center gap-3 overflow-hidden rounded-lg px-3 text-sm transition-colors {{ $active ? 'bg-blue-500/15 font-semibold text-white' : 'text-sidebar-foreground/75 hover:bg-white/[.06] hover:text-white' }}">
                        @if($active)<span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-amber-400"></span>@endif
                        @if($item['icon'] === 'dashboard')
                            <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-amber-300' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="1.8"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="1.8"/></svg>
                        @elseif($item['icon'] === 'formations')
                            <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-amber-300' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @elseif($item['icon'] === 'students')
                            <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-amber-300' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-11a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-amber-300' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-11a4 4 0 0 1 0 8m4 3v-2a4 4 0 0 0-3-3.87M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                        <span class="nav-text truncate">{{ $item['label'] }}</span>
                        @if($active)<span class="nav-active-dot ml-auto h-1.5 w-1.5 rounded-full bg-amber-300"></span>@endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="border-t border-sidebar-border p-4">
        <div class="flex items-center gap-3 rounded-xl bg-white/[.04] p-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-500/20 text-xs font-bold text-blue-100">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div class="sidebar-user min-w-0">
                <p class="truncate text-xs font-semibold">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                <p class="mt-0.5 text-[10px] text-sidebar-foreground/50">Espace de gestion</p>
            </div>
        </div>
    </div>
</aside>

<button id="sidebar-overlay" type="button" aria-label="Fermer le menu" class="fixed inset-0 z-40 hidden bg-slate-950/55 backdrop-blur-[2px] lg:hidden" onclick="closeMobileSidebar()"></button>

@push('scripts')
<script>
function setSidebarCollapsed(collapsed, persist = true) {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main-content');
    if (!sidebar) return;

    sidebar.dataset.collapsed = collapsed ? 'true' : 'false';
    sidebar.classList.toggle('w-16', collapsed);
    sidebar.classList.toggle('w-64', !collapsed);
    document.querySelectorAll('.nav-text, .logo-copy, .sidebar-user, #nav-label, .nav-active-dot').forEach(el => el.classList.toggle('hidden', collapsed));
    document.querySelectorAll('.nav-item').forEach(el => el.classList.toggle('justify-center', collapsed));
    document.querySelectorAll('.nav-item').forEach(el => el.classList.toggle('px-0', collapsed));
    document.querySelectorAll('#logo-full, #logo-mini').forEach(el => el.classList.toggle('hidden', (el.id === 'logo-full') === collapsed));
    document.querySelectorAll('#logo-mini').forEach(el => el.classList.toggle('flex', collapsed));

    if (main) {
        main.classList.toggle('lg:ml-16', collapsed);
        main.classList.toggle('lg:ml-64', !collapsed);
    }
    if (persist) localStorage.setItem('sidebar-collapsed', collapsed ? 'true' : 'false');
}

document.addEventListener('DOMContentLoaded', () => {
    const collapsed = localStorage.getItem('sidebar-collapsed') === 'true' && window.matchMedia('(min-width: 1024px)').matches;
    setSidebarCollapsed(collapsed, false);
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    setSidebarCollapsed(sidebar?.dataset.collapsed !== 'true');
}

function openMobileSidebar() {
    document.getElementById('sidebar')?.classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay')?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeMobileSidebar() {
    document.getElementById('sidebar')?.classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay')?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
</script>
@endpush
