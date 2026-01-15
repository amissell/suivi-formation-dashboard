<aside id="sidebar" 
       class="inset-y-0 left-0 z-50 bg-sidebar border-r border-sidebar-border 
              flex flex-col h-screen transition-all duration-300 ease-in-out
              -translate-x-full lg:translate-x-0 w-64"
       data-collapsed="false">

    <!-- Logo Section -->
    <div class="p-4 border-b border-sidebar-border flex-shrink-0">
        <div id="logo-full" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Must Négoce Academy" 
                 class="w-10 h-10 object-contain rounded" />
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold text-sidebar-foreground whitespace-nowrap">MUST-NÉGOCE</span>
                <span class="text-xs text-sidebar-foreground/70 whitespace-nowrap">ACADEMY</span>
            </div>
        </div>
        <div id="logo-mini" class="hidden justify-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" 
                 class="w-8 h-8 object-contain rounded" />
        </div>
    </div>

    <!-- Navigation - REMOVED flex-1 -->
    <nav class="p-3 overflow-y-auto">
        <h3 class="sidebar-text text-xs font-semibold text-sidebar-foreground/70 mb-3 uppercase tracking-wider px-3">
            Navigation
        </h3>
        <ul class="space-y-1">
            @php
            $navItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'grid'],
                ['route' => 'formations.*', 'label' => 'Formations', 'icon' => 'book'],
                ['route' => 'students.*', 'label' => 'Candidats', 'icon' => 'users'],
            ];
            @endphp
            
            @foreach($navItems as $item)
            <li class="nav-item relative group">
                <a href="{{ route(str_replace('.*', '.index', $item['route'])) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
                          {{ request()->routeIs($item['route']) 
                              ? 'bg-sidebar-accent text-white font-medium shadow-sm' 
                              : 'text-sidebar-foreground hover:bg-sidebar-accent/50' }}">
                    <!-- Icon based on type -->
                    @if($item['icon'] === 'grid')
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" stroke-width="2" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" stroke-width="2" rx="1"/>
                    </svg>
                    @elseif($item['icon'] === 'book')
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    @elseif($item['icon'] === 'users')
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    @endif
                    <span class="sidebar-text whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
                <!-- Tooltip for collapsed state -->
                <div class="nav-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-2 px-2 py-1 
                            bg-foreground text-background text-xs rounded shadow-lg
                            opacity-0 invisible group-hover:opacity-100 group-hover:visible
                            transition-all duration-200 whitespace-nowrap z-50 pointer-events-none">
                    {{ $item['label'] }}
                </div>
            </li>
            @endforeach
        </ul>
    </nav>
    <!-- Footer - ADDED mt-auto -->
    <div class="mt-auto p-3 border-t border-sidebar-border flex-shrink-0 space-y-1">
        
        <!-- Collapse Toggle (Desktop only) -->
        <button id="collapse-btn"
                class="hidden lg:flex items-center gap-3 w-full px-3 py-2.5 rounded-lg 
                       hover:bg-sidebar-accent/50 transition-all duration-200 text-sidebar-foreground">
            <svg id="collapse-icon" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <span class="sidebar-text whitespace-nowrap">Réduire</span>
        </button>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm" 
     onclick="closeMobileSidebar()"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const collapseBtn = document.getElementById('collapse-btn');
    const overlay = document.getElementById('sidebar-overlay');
    const mainContent = document.getElementById('main-content');
    
    // Desktop collapse functionality
    if (collapseBtn) {
        collapseBtn.addEventListener('click', toggleSidebarCollapse);
        
        // Restore saved state
        const savedState = localStorage.getItem('sidebar-collapsed');
        if (savedState === 'true') {
            collapseSidebar();
        }
    }
});

function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const isCollapsed = sidebar.dataset.collapsed === 'true';
    
    if (isCollapsed) {
        expandSidebar();
    } else {
        collapseSidebar();
    }
}

function collapseSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    
    sidebar.classList.remove('w-64');
    sidebar.classList.add('w-16');
    sidebar.dataset.collapsed = 'true';
    
    // Hide text elements
    document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
    
    // Switch logos
    document.getElementById('logo-full')?.classList.add('hidden');
    document.getElementById('logo-full')?.classList.remove('flex');
    document.getElementById('logo-mini')?.classList.remove('hidden');
    document.getElementById('logo-mini')?.classList.add('flex');
    
    // Rotate collapse icon
    document.getElementById('collapse-icon').style.transform = 'rotate(180deg)';
    
    // Show tooltips
    document.querySelectorAll('.nav-tooltip').forEach(el => el.classList.remove('hidden'));
    
    // Adjust main content margin
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
    document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
    
    // Switch logos
    document.getElementById('logo-full')?.classList.remove('hidden');
    document.getElementById('logo-full')?.classList.add('flex');
    document.getElementById('logo-mini')?.classList.add('hidden');
    document.getElementById('logo-mini')?.classList.remove('flex');
    
    // Reset collapse icon
    document.getElementById('collapse-icon').style.transform = 'rotate(0deg)';
    
    // Hide tooltips
    document.querySelectorAll('.nav-tooltip').forEach(el => el.classList.add('hidden'));
    
    // Adjust main content margin
    if (mainContent) {
        mainContent.classList.remove('lg:ml-16');
        mainContent.classList.add('lg:ml-64');
    }
    
    localStorage.setItem('sidebar-collapsed', 'false');
}

function closeMobileSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}

function openMobileSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}

</script>
@endpush