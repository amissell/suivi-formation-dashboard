<aside id="sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar border-r border-sidebar-border 
              flex flex-col h-screen transition-all duration-300 ease-in-out
              -translate-x-full lg:translate-x-0"
       data-collapsed="false">

    <!-- Logo Section -->
    <div class="p-4 border-b border-sidebar-border">
        <div id="logo-full" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Must Négoce Academy" 
                 class="w-10 h-10 object-contain rounded" />
            <div class="flex flex-col">
                <span class="text-sm font-bold text-sidebar-foreground">MUST-NÉGOCE</span>
                <span class="text-xs text-sidebar-foreground/70">ACADEMY</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <p class="text-xs font-semibold text-sidebar-foreground/70 uppercase tracking-wider mb-3 px-3">
            Navigation
        </p>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors
                          {{ request()->routeIs('dashboard') 
                              ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium' 
                              : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
                    <!-- Dashboard Icon -->
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" stroke-width="2" rx="1"/>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('formations.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors
                          {{ request()->routeIs('formations.*') 
                              ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium' 
                              : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
                    <!-- Formations Icon -->
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="nav-text">Formations</span>
                </a>
            </li>
            <li>
                <a href="{{ route('students.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors
                          {{ request()->routeIs('students.*') 
                              ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium' 
                              : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
                    <!-- Students Icon -->
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="nav-text">Candidats</span>
                </a>
            </li>
            <li>
                <a href="{{ route('groups.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors
                {{ request()->routeIs('groups.*')
                ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium'
                : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m6-2.13a4 4 0 1 0-6 0M21 12a3 3 0 1 0-6 0M3 12a3 3 0 1 0 6 0"/>
                </svg>
                <span class="nav-text">Groupes</span>
            </a>
        </li>
        </ul>
    </nav>
</aside>


<!-- Mobile Overlay -->
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" 
     onclick="closeMobileSidebar()"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Restore sidebar state from localStorage
    const savedState = localStorage.getItem('sidebar-collapsed');
    if (savedState === 'true') {
        collapseSidebar();
    }
});

function collapseSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    
    sidebar.classList.remove('w-64');
    sidebar.classList.add('w-16');
    sidebar.dataset.collapsed = 'true';
    
    // Hide text elements
    document.querySelectorAll('.nav-text').forEach(el => el.classList.add('hidden'));
    document.getElementById('nav-label').classList.add('hidden');
    
    // Switch logos
    document.getElementById('logo-full').classList.add('hidden');
    document.getElementById('logo-mini').classList.remove('hidden');
    document.getElementById('logo-mini').classList.add('flex');
    
    // Adjust main content
    if (mainContent) {
        mainContent.classList.remove('lg:ml-64');
        mainContent.classList.add('lg:ml-16');
    }
    
    localStorage.setItem('sidebar-collapsed', 'true');
}

function expandSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    
    sidebar.classList.remove('w-16');
    sidebar.classList.add('w-64');
    sidebar.dataset.collapsed = 'false';
    
    // Show text elements
    document.querySelectorAll('.nav-text').forEach(el => el.classList.remove('hidden'));
    document.getElementById('nav-label').classList.remove('hidden');
    
    // Switch logos
    document.getElementById('logo-full').classList.remove('hidden');
    document.getElementById('logo-mini').classList.add('hidden');
    document.getElementById('logo-mini').classList.remove('flex');
    
    // Adjust main content
    if (mainContent) {
        mainContent.classList.remove('lg:ml-16');
        mainContent.classList.add('lg:ml-64');
    }
    
    localStorage.setItem('sidebar-collapsed', 'false');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.dataset.collapsed === 'true') {
        expandSidebar();
    } else {
        collapseSidebar();
    }
}

function openMobileSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}

function closeMobileSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
</script>
@endpush
