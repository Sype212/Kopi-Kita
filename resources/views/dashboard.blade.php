<x-layouts.app :pageTitle="'Dashboard Utama'" :breadcrumb="'Operasional'">
    <div class="p-4 sm:p-6 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#1C1F1E]">
                    {{ now()->hour < 11 ? 'Selamat pagi' : (now()->hour < 15 ? 'Selamat siang' : 'Selamat sore') }}, {{ explode(' ', auth()->user()->name)[0] }} {{ now()->hour < 11 ? '☀️' : (now()->hour < 17 ? '🌤️' : '🌙') }}
                </h1>
                <p class="text-gray-500 text-sm mt-0.5">Berikut ringkasan aktivitas Kopi Kita hari ini.</p>
            </div>
            <a href="{{ route('transaksi.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-xl text-sm font-semibold transition-all shadow-xs card-hover btn-interactive">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>Transaksi baru</span>
            </a>
        </div>

        <!-- Stat Cards (Responsive: 1 col mobile, 2 col tablet, 4 col desktop) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Penjualan -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Total Penjualan Hari Ini</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">Rp{{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</div>
                <div class="text-xs text-green-600 mt-1 flex items-center gap-1 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    <span>12% dari kemarin</span>
                </div>
            </div>
            <!-- Jumlah Menu -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Jumlah Menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">{{ $jumlahMenu }} menu</div>
                <div class="text-xs text-gray-400 mt-1">{{ $jumlahMenuTersedia }} tersedia · {{ $jumlahMenuHabis }} habis</div>
            </div>
            <!-- Pesanan Hari Ini -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Pesanan Hari Ini</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">{{ $pesananHariIni }} pesanan</div>
                <div class="text-xs text-gray-400 mt-1">{{ $totalItemTerjual }} item terjual</div>
            </div>
            <!-- Rata-rata Transaksi -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Rata-rata Transaksi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-400 mt-1">Per transaksi lunas</div>
            </div>
        </div>

        <!-- Chart + Top Menu (Responsive: 1 col on mobile/tablet, 3 cols on desktop) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- Grafik Penjualan Per Jam -->
            <div class="col-span-1 lg:col-span-2 bg-white rounded-xl border border-[#E2DDD6] p-4 sm:p-5 card-hover">
                <div class="flex items-center justify-between mb-1">
                    <div>
                        <h3 class="text-sm font-semibold text-[#1C1F1E]">Penjualan per jam</h3>
                        <p class="text-xs text-gray-400">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }} · termasuk pajak</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-[#F5F3EE] text-xs font-semibold text-gray-600">Hari ini</span>
                </div>
                <!-- Simple bar chart -->
                @php
                    $amounts = array_column($salesPerHour, 'amount');
                    $maxAmount = !empty($amounts) ? max($amounts) : 0;
                    $maxSales = $maxAmount > 0 ? $maxAmount : 1;
                @endphp
                <div class="overflow-x-auto">
                    <div class="flex items-end gap-2 mt-4 h-32 min-w-[320px]">
                        @foreach($salesPerHour as $data)
                        @php
                            $barHeight = $data['amount'] > 0 ? max(6, round(($data['amount'] / $maxSales) * 88)) : 4;
                            $barBg = ($data['amount'] > 0 && $data['amount'] === $maxAmount) ? '#244B3B' : ($data['amount'] > 0 ? '#A3D0B9' : '#EDE9E0');
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            @if($data['amount'] > 0)
                            <span class="text-[9px] text-gray-500 font-medium group-hover:text-[#244B3B] transition-colors">{{ $data['label'] }}</span>
                            @else
                            <span class="text-[9px] text-gray-300">0</span>
                            @endif
                            <div class="w-full rounded-t-md transition-all hover:opacity-80"
                                 {!! 'style="height: ' . $barHeight . 'px; background-color: ' . $barBg . ';"' !!}>
                            </div>
                            <span class="text-[9px] text-gray-400 font-medium">{{ $data['hour'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Menu Paling Dicari -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 sm:p-5 card-hover">
                <h3 class="text-sm font-semibold text-[#1C1F1E] mb-3">Menu paling dicari</h3>
                @forelse($topMenus as $menu)
                <div class="flex items-center justify-between py-2.5 {{ !$loop->last ? 'border-b border-[#F0EDE7]' : '' }}">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#F5F3EE] flex items-center justify-center text-sm flex-shrink-0">
                            @if($menu->category->slug === 'kopi')☕
                            @elseif($menu->category->slug === 'non-kopi')🍵
                            @else🥐
                            @endif
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#1C1F1E]">{{ $menu->name }}</div>
                            <div class="text-[10px] text-gray-400">{{ $menu->total_sold ?? 0 }} {{ $menu->unit }} terjual</div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 text-green-500">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders + Periksa Stok (Responsive: 1 col mobile/tablet, 3 cols desktop) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Ringkasan Pesanan -->
            <div class="col-span-1 lg:col-span-2 bg-white rounded-xl border border-[#E2DDD6] p-4 sm:p-5 card-hover">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-[#1C1F1E]">Ringkasan Pesanan</h3>
                    <a href="{{ route('laporan.index') }}" class="text-xs text-[#244B3B] font-semibold hover:underline flex items-center gap-1 btn-interactive">
                        Lihat semua
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
                <!-- Status tabs -->
                @php
                    $today = now()->toDateString();
                    $menunggu = \App\Models\Order::whereDate('created_at', $today)->where('status', 'diproses')->count();
                    $diproses = \App\Models\Order::whereDate('created_at', $today)->where('status', 'siap_diambil')->count();
                    $selesai = \App\Models\Order::whereDate('created_at', $today)->where('status', 'selesai')->count();
                @endphp
                <div class="flex gap-2 mb-3.5 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] bg-amber-100 text-amber-800 font-semibold">
                        Menunggu {{ $menunggu }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] bg-blue-100 text-blue-800 font-semibold">
                        Diproses {{ $diproses }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] bg-green-100 text-green-800 font-semibold">
                        Selesai {{ $selesai }}
                    </span>
                </div>
                <!-- Table Responsive Wrapper -->
                <div class="overflow-x-auto">
                    <table class="w-full text-xs min-w-[500px]">
                        <thead>
                            <tr class="text-gray-400 border-b border-[#F0EDE7]">
                                <th class="text-left pb-2 font-semibold">Pesanan / Waktu</th>
                                <th class="text-left pb-2 font-semibold">Pelanggan</th>
                                <th class="text-left pb-2 font-semibold">Item</th>
                                <th class="text-left pb-2 font-semibold">Total</th>
                                <th class="text-left pb-2 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F5F3EE]">
                            @forelse($recentOrders as $order)
                            @php $badge = $order->statusBadge(); @endphp
                            <tr class="hover:bg-[#FAFAF8] transition-colors">
                                <td class="py-2.5">
                                    <div class="font-bold text-[#1C1F1E]">{{ $order->orderLabel() }}</div>
                                    <div class="text-gray-400 text-[10px]">{{ $order->created_at->format('H.i') }}</div>
                                </td>
                                <td class="py-2.5 text-gray-600 font-medium">{{ $order->locationLabel() }}</td>
                                <td class="py-2.5 text-gray-600">{{ $order->items->count() }} item</td>
                                <td class="py-2.5 font-bold text-[#1C1F1E]">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-2.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada pesanan hari ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Periksa Stok & Motivasi -->
            <div class="space-y-4">
                <!-- Periksa Stok -->
                <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 sm:p-5 card-hover">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-base">🧇</span>
                        <h3 class="text-sm font-semibold text-[#1C1F1E]">Periksa stok hari ini</h3>
                    </div>
                    @if($lowStockProduct)
                    <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                        <span class="font-semibold text-[#1C1F1E]">{{ $lowStockProduct->name }}</span> tersisa <span class="font-bold text-amber-600">{{ $lowStockProduct->stock }} {{ $lowStockProduct->unit }}</span>.
                        Siapkan stok tambahan sebelum sore.
                    </p>
                    @else
                    <p class="text-xs text-gray-500 mb-3">Semua stok menu dalam kondisi baik.</p>
                    @endif
                    <a href="{{ route('menu.index') }}"
                       class="w-full block text-center py-2.5 px-3 rounded-xl border border-[#E2DDD6] text-xs font-semibold text-[#1C1F1E] hover:bg-[#F5F3EE] transition-all card-hover btn-interactive">
                        Kelola data menu
                    </a>
                </div>
                <!-- Motivasi -->
                <div class="bg-[#244B3B] rounded-xl p-5 shadow-xs text-white">
                    <p class="font-bold text-sm mb-1">Layanan baik, hari yang baik.</p>
                    <p class="text-white/80 text-xs leading-relaxed">Pastikan pesanan sesuai sebelum pembayaran. Selamat melayani pelanggan Kopi Kita!</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
