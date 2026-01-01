<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Must Negoce Academy'))</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-background flex">
        
        <!-- Sidebar (always included for authenticated users) -->
        @auth
            @include('components.sidebar')
        @endauth
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col @auth ml-0 lg:ml-64 @endauth">
            
            <!-- Top Navigation Bar -->
            @auth
                <header class="bg-white shadow-sm border-b border-border sticky top-0 z-40">
                    <div class="flex items-center justify-between px-4 py-3">
                        <!-- Mobile Menu Toggle -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Page Title -->
                        <h1 class="text-lg font-semibold text-foreground">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        
                        <!-- User Menu -->
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-muted-foreground hidden sm:block">
                                {{ Auth::user()->name }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                    Deconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </header>
            @endauth
            
            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6">
                {{-- Support for traditional Blade sections --}}
                @hasSection('content')
                    @yield('content')
                {{-- Support for component-style $slot --}}
                @elseif(isset($slot))
                    {{ $slot }}
                @endif
            </main>
            
        </div>
    </div>
    
    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
