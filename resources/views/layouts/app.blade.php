<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Must Négoce Academy - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="h-14 border-b border-border bg-card flex items-center px-4">
                <button id="sidebar-toggle" class="p-2 hover:bg-muted rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="ml-4 text-lg font-semibold text-foreground">Must Négoce Academy Dashboard</h1>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 bg-muted/30">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Container (for notifications) -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @stack('scripts')
</body>
</html>