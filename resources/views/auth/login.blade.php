<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Must Négoce Academy</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .shimmer {
            background: linear-gradient(to right, transparent 0%, rgba(110, 193, 228, 0.3) 50%, transparent 100%);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-900 via-primary-800 to-accent-700">
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Floating Circles -->
            <div class="absolute top-20 left-20 w-72 h-72 bg-[#6EC1E4]/10 rounded-full blur-3xl float-animation"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-[#004CA2]/20 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#6EC1E4]/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Login Card -->
        <div class="w-full max-w-md relative z-10 px-4">
            
            <!-- Card -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-[#6EC1E4]/20">
                
                <!-- Header with Logo -->
                <div class="bg-gradient-to-r from-primary-700 to-primary-900 px-8 py-6 text-center">                    <!-- Shimmer Effect -->
                    <div class="absolute inset-0 shimmer"></div>
                    
                    <!-- Logo Container -->
                    <div class="relative flex justify-center mb-4">
                        <div class="relative group">
                            <!-- Glow effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#6EC1E4] to-white rounded-2xl blur-2xl opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>
                            
                            <!-- Logo -->
                            <div class="relative bg-white p-4 rounded-2xl shadow-2xl ring-4 ring-white/50 transform group-hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('images/logo.jpg') }}" 
                                     alt="Must Négoce Academy" 
                                     class="h-20 w-20 object-contain">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-white mb-2 relative">
                        Must Négoce Academy
                    </h1>
                    <p class="text-[#6EC1E4] text-sm font-medium relative">
                        Administration • Tableau de Bord
                    </p>
                </div>

                <!-- Login Form -->
                <div class="px-8 py-10">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Bienvenue !</h2>
                        <p class="text-gray-600 text-sm">Connectez-vous pour accéder au tableau de bord</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse Email
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#6EC1E4] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus
                                       class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-[#6EC1E4]/50 focus:border-[#6EC1E4] transition-all duration-200"
                                       placeholder="admin@mustnegoce.com">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mot de passe
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#6EC1E4] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required
                                       class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-[#6EC1E4]/50 focus:border-[#6EC1E4] transition-all duration-200"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       name="remember" 
                                       class="w-4 h-4 rounded border-gray-300 text-[#6EC1E4] focus:ring-[#6EC1E4] focus:ring-offset-0 transition-colors">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                    Se souvenir de moi
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full relative bg-gradient-to-r from-[#6EC1E4] to-[#004CA2] text-white py-4 px-6 rounded-xl font-bold text-base shadow-lg shadow-[#6EC1E4]/30 hover:shadow-xl hover:shadow-[#6EC1E4]/40 focus:outline-none focus:ring-4 focus:ring-[#6EC1E4]/50 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] overflow-hidden group">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Se connecter
                            </span>
                            <!-- Hover effect -->
                            <div class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-8 py-5 bg-gradient-to-r from-gray-50 to-[#6EC1E4]/5 border-t border-gray-100">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[#004CA2]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-xs text-gray-600 font-medium">
                            Accès réservé à l'administration
                        </p>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <p class="text-center text-white/80 text-sm mt-8 font-medium">
                © 2025 Must Négoce Academy - Tous droits réservés
            </p>
        </div>
    </div>

</body>
</html>