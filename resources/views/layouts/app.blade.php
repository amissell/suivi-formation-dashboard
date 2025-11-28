<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-muted/30">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-sidebar-background border-r border-sidebar-border">
            <div class="p-4 border-b border-sidebar-border">
                <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10" alt="Logo">
                <span class="text-sm font-bold text-sidebar-foreground">MUST-NÉGOCE</span>
            </div>
            <nav class="p-4">
                <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent">
                    Dashboard
                </a>
                <a href="/formations" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent">
                    Formations
                </a>
                <a href="/students" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-sidebar-accent">
                    Students
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="h-14 border-b border-border bg-card flex items-center px-4">
                <h1 class="text-lg font-semibold text-foreground">Must Négoce Academy Dashboard</h1>
            </header>
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
