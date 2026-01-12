<aside id="sidebar" class="w-64 bg-white dark:bg-gradient-to-b dark:from-[#004CA2] dark:via-[#003a7d] dark:to-[#002855] border-r border-gray-200 dark:border-[#6EC1E4]/20 transition-all duration-300 flex flex-col h-screen sticky top-0 shadow-xl max-lg:fixed max-lg:inset-y-0 max-lg:left-0 max-lg:z-50 max-lg:-translate-x-full">

    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200 dark:border-[#6EC1E4]/20 flex-shrink-0 bg-gradient-to-br from-[#6EC1E4]/5 to-[#004CA2]/5">
        <div class="flex flex-col items-center space-y-3">
            <div class="relative group">
                <!-- Glow effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#6EC1E4] to-[#004CA2] rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                
                <!-- Logo -->
                <div class="relative bg-white dark:bg-white/10 p-3 rounded-2xl shadow-lg ring-2 ring-[#6EC1E4]/20 group-hover:ring-[#6EC1E4]/40 transition-all duration-300 group-hover:scale-105">
                    <img src="{{ asset('images/logo.jpg') }}" 
                         alt="Must Négoce Academy" 
                         class="w-16 h-16 object-contain" />
                </div>
            </div>
            
            <!-- Academy Name -->
            <div class="text-center">
                <h1 class="text-sm font-bold text-gray-900 dark:text-white">Must Négoce Academy</h1>
                <p class="text-xs text-[#6EC1E4] dark:text-[#6EC1E4]/80 font-medium">Administration</p>
            </div>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="p-4 border-b border-gray-200 dark:border-[#6EC1E4]/20">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-[#6EC1E4]/10 transition-all cursor-pointer group">
            <!-- Avatar with gradient -->
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6EC1E4] to-[#004CA2] flex items-center justify-center font-bold text-white text-sm shadow-lg ring-2 ring-[#6EC1E4]/30 group-hover:ring-[#6EC1E4]/50 transition-all">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                </div>
                <!-- Online indicator -->
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-[#004CA2] rounded-full"></span>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-500 dark:text-[#6EC1E4]/60 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
            </div>
            
            <!-- Dropdown arrow -->
            <svg class="w-4 h-4 text-gray-400 dark:text-[#6EC1E4]/50 group-hover:text-[#6EC1E4] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto custom-scrollbar">
        <h3 class="text-xs font-bold text-gray-400 dark:text-[#6EC1E4]/60 mb-3 uppercase tracking-wider px-3">Menu Principal</h3>
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#6EC1E4] to-[#4fa8c5] text-white shadow-lg shadow-[#6EC1E4]/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#6EC1E4]/10 hover:text-[#004CA2] dark:hover:text-[#6EC1E4]' }}">
                    <!-- Active indicator -->
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></span>
                    @endif
                    
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-gray-100 dark:bg-white/5 group-hover:bg-[#6EC1E4]/20' }} transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="18" 
                             height="18" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2.5" 
                             stroke-linecap="round" 
                             stroke-linejoin="round"
                             class="transition-transform duration-200 {{ request()->routeIs('dashboard') ? '' : 'group-hover:scale-110' }}">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    @endif
                </a>
            </li>

            <!-- Formations -->
            <li>
                <a href="{{ route('formations.index') }}" 
                   class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('formations.*') ? 'bg-gradient-to-r from-[#004CA2] to-[#003580] text-white shadow-lg shadow-[#004CA2]/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#6EC1E4]/10 hover:text-[#004CA2] dark:hover:text-[#6EC1E4]' }}">
                    <!-- Active indicator -->
                    @if(request()->routeIs('formations.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#6EC1E4] rounded-r-full"></span>
                    @endif
                    
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('formations.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-white/5 group-hover:bg-[#6EC1E4]/20' }} transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="18" 
                             height="18" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2.5" 
                             stroke-linecap="round" 
                             stroke-linejoin="round"
                             class="transition-transform duration-200 {{ request()->routeIs('formations.*') ? '' : 'group-hover:scale-110' }}">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Formations</span>
                    @if(request()->routeIs('formations.*'))
                        <span class="ml-auto w-2 h-2 rounded-full bg-[#6EC1E4] animate-pulse"></span>
                    @endif
                </a>
            </li>

            <!-- Students/Candidats -->
            <li>
                <a href="{{ route('students.index') }}" 
                   class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('students.*') ? 'bg-gradient-to-r from-[#6EC1E4] to-[#4fa8c5] text-white shadow-lg shadow-[#6EC1E4]/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#6EC1E4]/10 hover:text-[#004CA2] dark:hover:text-[#6EC1E4]' }}">
                    <!-- Active indicator -->
                    @if(request()->routeIs('students.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-r-full"></span>
                    @endif
                    
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('students.*') ? 'bg-white/20' : 'bg-gray-100 dark:bg-white/5 group-hover:bg-[#6EC1E4]/20' }} transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="18" 
                             height="18" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2.5" 
                             stroke-linecap="round" 
                             stroke-linejoin="round"
                             class="transition-transform duration-200 {{ request()->routeIs('students.*') ? '' : 'group-hover:scale-110' }}">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-sm">Candidats</span>
                    @if(request()->routeIs('students.*'))
                        <span class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    @endif
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200 dark:border-[#6EC1E4]/20"></div>

        <!-- Settings Section -->
        <h3 class="text-xs font-bold text-gray-400 dark:text-[#6EC1E4]/60 mb-3 uppercase tracking-wider px-3">Paramètres</h3>
        <ul class="space-y-1">
            <!-- Dark Mode Toggle -->
            <li>
                <button onclick="toggleDarkMode()" 
                        class="group relative flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#6EC1E4]/10 hover:text-[#004CA2] dark:hover:text-[#6EC1E4] transition-all duration-200">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-white/5 group-hover:bg-[#6EC1E4]/20 transition-all">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg id="sun-icon" 
                             class="w-5 h-5 hidden transition-transform duration-200 group-hover:rotate-180" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24" 
                             stroke-width="2.5" 
                             stroke-linecap="round" 
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>

                        <!-- Moon icon (shown in light mode) -->
                        <svg id="moon-icon" 
                             class="w-5 h-5 block transition-transform duration-200 group-hover:rotate-12" 
                             fill="none" 
                             stroke="currentColor" 
                             viewBox="0 0 24 24" 
                             stroke-width="2.5" 
                             stroke-linecap="round" 
                             stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </div>

                    <span class="font-semibold text-sm" id="theme-text">Mode Sombre</span>
                </button>
            </li>
        </ul>
    </nav>

    <!-- Logout Section -->
    <div class="p-4 border-t border-gray-200 dark:border-[#6EC1E4]/20 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" 
                    class="group relative flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-600/20 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 border border-transparent hover:border-red-200 dark:hover:border-red-600/30">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-white/5 group-hover:bg-red-100 dark:group-hover:bg-red-600/20 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         width="18" 
                         height="18" 
                         viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="currentColor" 
                         stroke-width="2.5" 
                         stroke-linecap="round" 
                         stroke-linejoin="round"
                         class="transition-transform duration-200 group-hover:translate-x-1">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Déconnexion</span>
            </button>
        </form>
    </div>

</aside>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" 
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"
     onclick="toggleMobileSidebar()">
</div>

<!-- Custom Scrollbar Styles -->
<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(110, 193, 228, 0.05);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(110, 193, 228, 0.3);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(110, 193, 228, 0.5);
}

/* Light mode scrollbar */
:root:not(.dark) .custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
}

:root:not(.dark) .custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 76, 162, 0.2);
}

:root:not(.dark) .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 76, 162, 0.4);
}
</style>

@push('scripts')
<script>
    // Mobile Sidebar Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }

    // Sidebar toggle button in header
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleMobileSidebar);
    }

    // Dark Mode Toggle Function (synced with existing function)
    window.toggleDarkMode = function() {
        const html = document.documentElement;
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        const themeText = document.getElementById('theme-text');
        
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.theme = 'light';
            if (sunIcon && moonIcon && themeText) {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                themeText.textContent = 'Mode Sombre';
            }
        } else {
            html.classList.add('dark');
            localStorage.theme = 'dark';
            if (sunIcon && moonIcon && themeText) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
                themeText.textContent = 'Mode Clair';
            }
        }
    }

    // Initialize theme icons on page load
    document.addEventListener('DOMContentLoaded', function() {
        const html = document.documentElement;
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        const themeText = document.getElementById('theme-text');
        
        if (html.classList.contains('dark')) {
            if (sunIcon && moonIcon && themeText) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
                themeText.textContent = 'Mode Clair';
            }
        }
    });

    // Close mobile sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar && overlay && !sidebar.classList.contains('-translate-x-full')) {
                toggleMobileSidebar();
            }
        }
    });
</script>
@endpush