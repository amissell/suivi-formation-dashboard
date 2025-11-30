<aside id="sidebar" class="w-64 bg-sidebar border-r border-sidebar-border transition-all duration-300">
    <!-- Logo Section -->
    <div class="p-4 border-b border-sidebar-border">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Must Négoce Academy" class="w-10 h-10 object-contain">
            <div class="flex flex-col">
                <span class="text-sm font-bold text-sidebar-foreground">MUST-NÉGOCE</span>
                <span class="text-xs text-sidebar-foreground/70">ACADEMY</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4">
        <h3 class="text-xs font-semibold text-sidebar-foreground/70 mb-2">Navigation</h3>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent {{ request()->routeIs('dashboard') ? 'bg-sidebar-accent font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span class="text-sidebar-foreground">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('formations.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent {{ request()->routeIs('formations.*') ? 'bg-sidebar-accent font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <span class="text-sidebar-foreground">Formations</span>
                </a>
            </li>
            <li>
                <a href="{{ route('students.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent {{ request()->routeIs('students.*') ? 'bg-sidebar-accent font-medium' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-sidebar-foreground">Students</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

@push('scripts')
<script>
    // Sidebar toggle functionality
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-16');
    });
</script>
@endpush
