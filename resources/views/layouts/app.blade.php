<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Must Négoce Academy - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Dark mode initialization
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        }
    </script>
    @stack('styles')
</head>
<body class="bg-background text-foreground antialiased">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-64 transition-all duration-300" id="main-content">
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center border-b border-border bg-card/95 px-3 shadow-sm backdrop-blur-xl sm:px-6">
                <button type="button" onclick="openMobileSidebar()" aria-label="Ouvrir le menu" class="mr-2 inline-flex h-10 w-10 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-muted hover:text-foreground lg:hidden">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
                <button type="button" onclick="toggleSidebar()" aria-label="Réduire ou agrandir le menu" class="hidden h-10 w-10 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-muted hover:text-foreground lg:inline-flex">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="16" rx="2" stroke-width="1.7"/><path d="M9 4v16" stroke-width="1.7" stroke-linecap="round"/></svg>
                </button>

                @php
                    $pageTitle = request()->routeIs('dashboard') ? 'Dashboard' : (request()->routeIs('students.*') ? 'Candidats' : (request()->routeIs('formations.*') ? 'Formations' : (request()->routeIs('groups.*') ? 'Groupes' : 'Administration')));
                @endphp
                <div class="ml-1 min-w-0 sm:ml-3">
                    <p class="truncate text-sm font-semibold text-foreground sm:text-base">{{ $pageTitle }}</p>
                    <p class="hidden text-[11px] text-muted-foreground sm:block">Must Négoce Academy <span class="mx-1 text-border">/</span> Gestion</p>
                </div>

                <div class="ml-auto flex items-center gap-1.5 sm:gap-3">
                    <button type="button" onclick="toggleDarkMode()" aria-label="Changer le thème" title="Changer le thème" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-muted-foreground transition hover:bg-muted hover:text-foreground">
                        <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20.4 15.4A8.5 8.5 0 0 1 8.6 3.6 8.8 8.8 0 1 0 20.4 15.4Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="4" stroke-width="1.8"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                    <div class="hidden h-8 w-px bg-border sm:block"></div>
                    <div class="hidden items-center gap-2.5 sm:flex">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800 dark:bg-blue-400/15 dark:text-blue-200">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                        <span class="max-w-36 truncate text-sm font-medium text-foreground">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex h-10 items-center gap-2 rounded-lg px-2.5 text-sm font-medium text-muted-foreground transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-400/10 dark:hover:text-red-300 sm:px-3" aria-label="Déconnexion" title="Déconnexion">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="hidden sm:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 bg-background p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>

