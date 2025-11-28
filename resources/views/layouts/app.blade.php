<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Must Négoce Academy Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary border-r border-border">
            <div class="h-14 flex items-center px-4 border-b border-primary-foreground/20">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-8 rounded">
                <span class="ml-3 text-lg font-semibold text-primary-foreground">Must Négoce</span>
            </div>
            <nav class="p-4 space-y-2">
                <a href="/" class="flex items-center gap-3 px-4 py-2 rounded-lg text-primary-foreground hover:bg-primary-foreground/10">
                    Dashboard
                </a>
                <a href="/formations" class="flex items-center gap-3 px-4 py-2 rounded-lg text-primary-foreground hover:bg-primary-foreground/10">
                    Formations
                </a>
                <a href="/students" class="flex items-center gap-3 px-4 py-2 rounded-lg text-primary-foreground hover:bg-primary-foreground/10">
                    Étudiants
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="h-14 border-b border-border bg-card flex items-center px-6">
                <h1 class="text-lg font-semibold text-foreground">Must Négoce Academy Dashboard</h1>
            </header>
            <main class="flex-1 p-6 bg-muted/30">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
