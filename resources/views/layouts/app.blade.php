<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Must Négoce Academy - @yield('title', 'Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token()}}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' inlocalStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
        ){
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        function toggleDarkMode(){
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            }
            </script>
            @stack('styles')
        </head>
        <body class="bg-background text-foreground antialiased">
            <div class="flex h-screen overflow-hidden"><!-- Sidebar -->
                @include('components.sidebar')
                <div class="flex-1 flex flex-col overflow-hidden">
                    <header class="h-16 border-b border-border bg-card flex items-center px-4
                    md:px-6 flex-shrink-0">
                    <button id="sidebar-toggle"
                    class="p-2
                    hover:bg-muted
                    rounded-md
                    focus:outline-none
                    focus:ring-2 focus:ring-primary transition-colors"
                    aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg"
                    width="20" height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <h1 class="ml-4 text-lg font-semibold text-foreground hidden md:block">
                Must Négoce Academy
            </h1>
            <div class="ml-auto flexitems-center gap-2">
                <span
                class="text-sm
                text-muted-foreground">{{
                auth()->user()->name ?? 'Admin'}}</span>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-muted/30">
                @if(session('success'))
                <div
                class="mb-4
                p-4
                bg-green-50
                dark:bg-green-900/20
                borderborder-green-200 dark:border-green-800 rounded-lg">
                <p
                class="text-sm
                text-green-800
                dark:text-green-200">{{
                session('success') }}</p>
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200
                dark:border-red-800 rounded-lg">
                <p
                class="text-sm
                text-red-800
                dark:text-red-200">{{
                session('error') }}</p>
                </div>
                @endif
                @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200
                dark:border-red-800 rounded-lg">
                <ul class="list-disc list-inside text-smtext-red-800 dark:text-red-200">
                    @foreach($errors->all()as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<div id="toast-container"
class="fixed top-4 right-4 z-50 space-y-2">
</div>
@stack('scripts')
</body>
</html>