<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login – Sistem Kasir Kopi Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="h-full bg-[#F5F3EE]">
    <div class="min-h-screen flex">
        <!-- Left Panel – Foto Kafe -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=1200&auto=format&fit=crop"
                 alt="Suasana Kopi Kita"
                 class="absolute inset-0 w-full h-full object-cover">
            <!-- Overlay gelap -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10"></div>
            <!-- Konten overlay -->
            <div class="relative z-10 flex flex-col justify-between p-10 w-full">
                <!-- Logo -->
                <div class="flex items-center gap-2 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                        <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                    </svg>
                    <span class="font-bold text-lg tracking-tight">kopi kita.</span>
                </div>
                <!-- Tagline -->
                <div>
                    <h1 class="text-white font-bold text-4xl leading-tight mb-3">
                        Secangkir hangat.<br>Layanan lebih dekat.
                    </h1>
                    <p class="text-white/70 text-sm leading-relaxed mb-4">
                        Dari pesanan pertama hingga laporan terakhir, semua tercatat rapi di Kopi Kita.
                    </p>
                    <div class="flex items-center gap-1.5 text-white/60 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        Jl. Cihapit No. 18, Bandung
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel – Form Login -->
        <div class="flex-1 flex flex-col justify-center px-8 md:px-16 lg:px-20 bg-[#F5F3EE]">
            <div class="max-w-sm w-full mx-auto">
                <!-- Header form -->
                <div class="mb-8">
                    <div class="text-xs font-semibold text-[#244B3B] uppercase tracking-widest mb-2">
                        SISTEM INFORMASI KASIR KOPI KITA
                    </div>
                    <h2 class="text-3xl font-bold text-[#1C1F1E] mb-1">Selamat datang kembali</h2>
                    <p class="text-gray-500 text-sm">Masuk untuk memulai giliran dan melayani pelanggan hari ini.</p>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-[#1C1F1E] mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="ayu.pratama"
                                   autocomplete="username"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#E2DDD6] bg-white text-[#1C1F1E] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] transition-all"
                                   required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-[#1C1F1E] mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <input type="password"
                                   name="password"
                                   placeholder="••••••••••"
                                   autocomplete="current-password"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#E2DDD6] bg-white text-[#1C1F1E] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] transition-all"
                                   required>
                        </div>
                    </div>

                    <!-- Ingat saya & Lupa password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 text-[#244B3B] focus:ring-[#244B3B]" checked>
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <span class="text-sm text-gray-400 cursor-default">Lupa password?</span>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-lg font-medium text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                        Masuk ke sistem
                    </button>
                </form>

                <!-- Footer info -->
                <div class="mt-6 text-center space-y-2">
                    <div class="flex items-center justify-center gap-1.5 text-xs text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                            <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                        </svg>
                        Akun dikelola oleh admin Kopi Kita.
                    </div>
                    <p class="text-xs text-gray-400">Butuh bantuan? Hubungi admin kafe.</p>
                    <p class="text-xs text-gray-300 mt-4">© {{ date('Y') }} Kopi Kita · Diracik untuk layanan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
