<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Kasir' }} – Kopi Kita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-cream: #F5F3EE;
            --color-cream-dark: #EDE9E0;
            --color-green: #244B3B;
            --color-green-hover: #1a3a2d;
            --color-green-light: #E8F0EB;
            --color-charcoal: #1C1F1E;
            --color-muted: #6B7280;
            --color-border: #E2DDD6;
        }
        body { font-family: 'Inter', 'Instrument Sans', system-ui, sans-serif; }
        .sidebar-link.active { background: var(--color-green); color: white; }
        .sidebar-link.active svg { color: white; }
        .sidebar-link:not(.active):hover { background: var(--color-green-light); }
        .btn-primary { background: var(--color-green); color: white; }
        .btn-primary:hover { background: var(--color-green-hover); }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-[#F5F3EE]">
    <div class="flex h-screen overflow-hidden">
        <!-- MOBILE BACKDROP -->
        <div id="sidebarBackdrop" onclick="toggleMobileSidebar()"
             class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
        <aside id="mainSidebar"
               class="fixed lg:static inset-y-0 left-0 z-50 w-52 lg:w-44 bg-white border-r border-[#E2DDD6] flex flex-col h-full overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none">
            <!-- Logo & Close on mobile -->
            <div class="p-4 pb-3 border-b border-[#E2DDD6] flex items-center justify-between">
                <div class="flex items-center gap-2 mb-0.5">
                    <div class="w-7 h-7 bg-[#244B3B] rounded-lg flex items-center justify-center text-white text-sm flex-shrink-0 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                            <line x1="6" y1="1" x2="6" y2="4"/>
                            <line x1="10" y1="1" x2="10" y2="4"/>
                            <line x1="14" y1="1" x2="14" y2="4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-[#1C1F1E] text-sm leading-tight">kopi kita.</div>
                        <div class="text-[10px] text-gray-500 leading-tight uppercase tracking-wide">Sistem Kasir</div>
                    </div>
                </div>
                <!-- Close Button on Mobile -->
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden text-gray-400 hover:text-gray-600 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Branch -->
            <div class="px-3 pt-3 pb-2">
                <div class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mb-1">Cabang Aktif</div>
                <div class="flex items-center justify-between cursor-pointer group">
                    <span class="text-[11px] font-medium text-[#1C1F1E]">{{ auth()->user()?->branch ?? 'Kopi Kita • Bandung' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3 text-gray-400">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-2 pt-2 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-medium text-gray-600 btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-500 flex-shrink-0">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard Utama
                </a>
                <a href="{{ route('transaksi.index') }}" class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-medium text-gray-600 btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-500 flex-shrink-0">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <line x1="8" y1="21" x2="16" y2="21"/>
                        <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    Transaksi Kasir
                </a>
                <a href="{{ route('menu.index') }}" class="sidebar-link {{ request()->routeIs('menu.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-medium text-gray-600 btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-500 flex-shrink-0">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <line x1="9" y1="12" x2="15" y2="12"/>
                        <line x1="9" y1="16" x2="15" y2="16"/>
                    </svg>
                    Data Menu
                </a>
                <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-xs font-medium text-gray-600 btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-500 flex-shrink-0">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    Laporan & Struk
                </a>
            </nav>

            <!-- User info & Logout -->
            <div class="p-3 border-t border-[#E2DDD6]">
                @auth
                <div class="mb-2">
                    <div class="text-[9px] text-gray-400 uppercase tracking-widest font-semibold mb-1">
                        GILIRAN • {{ auth()->user()->shift ?? '08.00-16.00' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#244B3B] text-white text-[9px] font-bold flex items-center justify-center flex-shrink-0 shadow-xs">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[11px] font-semibold text-[#1C1F1E] truncate leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-gray-500 capitalize">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-[11px] text-gray-500 hover:text-red-500 transition-colors w-full py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Keluar sistem
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOP HEADER -->
            <header class="bg-white border-b border-[#E2DDD6] px-4 sm:px-6 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <!-- Hamburger Toggle Button -->
                    <button type="button" onclick="toggleMobileSidebar()"
                            class="lg:hidden p-1.5 -ml-1 text-gray-600 hover:text-[#244B3B] rounded-lg hover:bg-[#F5F3EE] transition-colors"
                            aria-label="Menu navigasi">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                            <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

                    <div class="text-xs text-gray-500 truncate">
                        {{ $breadcrumb ?? 'Operasional' }} / <span class="text-[#1C1F1E] font-medium">{{ $pageTitle ?? 'Dashboard' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="hidden sm:block text-xs text-gray-500">
                        {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium border border-[#244B3B] text-[#244B3B]">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                        Toko buka
                    </span>
                    <button class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto animate-fade-in">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
