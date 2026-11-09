<x-layouts.app :pageTitle="'Laporan & Struk'" :breadcrumb="'Operasional'">
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-[#1C1F1E]">Laporan & detail struk</h1>
                <p class="text-sm text-gray-500 mt-0.5">Tinjau penjualan harian dan cetak ulang nota transaksi.</p>
            </div>
            <!-- Action Buttons (Diperlebar dan rapi sesuai arahan) -->
            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <a href="{{ route('laporan.export-csv', ['from' => $dateFrom, 'to' => $dateTo]) }}"
                   class="inline-flex items-center justify-center gap-3 px-7 py-3 min-w-[210px] border border-[#E2DDD6] bg-white hover:bg-[#F5F3EE] text-[#1C1F1E] rounded-xl text-sm font-semibold shadow-xs hover:shadow-md transition-all card-hover whitespace-nowrap btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-emerald-600 flex-shrink-0">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span class="tracking-wide">Unduh Excel (CSV)</span>
                </a>
                <a href="{{ route('laporan.cetak', ['from' => $dateFrom, 'to' => $dateTo]) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-3 px-7 py-3 min-w-[210px] bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-xl text-sm font-semibold shadow-xs hover:shadow-md transition-all card-hover whitespace-nowrap btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 flex-shrink-0">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    <span class="tracking-wide">Cetak Rekap (A4)</span>
                </a>
            </div>
        </div>

        <!-- Date Filter -->
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-3 mb-6 bg-white p-3.5 rounded-xl border border-[#E2DDD6]">
            <div class="relative flex-1 sm:flex-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="date" name="from" value="{{ $dateFrom }}"
                       class="w-full pl-9 pr-3 py-2 rounded-lg border border-[#E2DDD6] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
            </div>
            <div class="relative flex-1 sm:flex-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="date" name="to" value="{{ $dateTo }}"
                       class="w-full pl-9 pr-3 py-2 rounded-lg border border-[#E2DDD6] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
            </div>
            <button type="submit"
                    class="px-5 py-2 bg-[#244B3B] text-white rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#1a3a2d] transition-colors btn-interactive flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Terapkan filter
            </button>
            <a href="{{ route('laporan.index') }}"
               class="px-4 py-2 border border-[#E2DDD6] bg-white text-gray-600 rounded-lg text-xs sm:text-sm font-medium hover:bg-[#F5F3EE] transition-colors">
                Hari ini
            </a>
        </form>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Total penjualan harian</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-400 mt-1">Termasuk pajak · {{ \Carbon\Carbon::parse($dateFrom)->locale('id')->isoFormat('D MMM YYYY') }}</div>
            </div>
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Transaksi lunas</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <path d="m9 12 2 2 4-4"/><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">{{ $totalTransaksi }} transaksi</div>
                <div class="text-xs text-gray-400 mt-1">Tidak ada transaksi dibatalkan</div>
            </div>
            <div class="bg-white rounded-xl border border-[#E2DDD6] p-4 card-hover">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Item terjual</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-gray-300">
                        <path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1C1F1E]">{{ $totalItem }} item</div>
                <div class="text-xs text-gray-400 mt-1">Rata-rata transaksi Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Main 2 column layout (Responsive flex-col lg:flex-row) -->
        <div class="flex flex-col lg:flex-row gap-5">
            <!-- Riwayat Transaksi -->
            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-xl border border-[#E2DDD6] overflow-hidden">
                    <div class="px-5 py-3 border-b border-[#E2DDD6] flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[#1C1F1E]">Riwayat transaksi</h3>
                        <span class="text-xs text-gray-400">Terbaru lebih dulu</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-[#F0EDE7] text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                                    <th class="py-2.5 px-4 font-semibold">Transaksi</th>
                                    <th class="py-2.5 px-3 font-semibold">Metode</th>
                                    <th class="py-2.5 px-3 font-semibold">Total Bayar</th>
                                    <th class="py-2.5 px-3 font-semibold">Status</th>
                                    <th class="py-2.5 px-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#F5F3EE]">
                                @forelse($orders as $order)
                                @php $badge = $order->statusBadge(); @endphp
                                <tr class="hover:bg-[#FAFAF8] transition-colors {{ $selectedOrder && $selectedOrder->id === $order->id ? 'bg-[#F0F7F3]' : '' }}">
                                    <td class="py-3 px-4">
                                        <div class="text-xs font-bold text-[#1C1F1E]">{{ $order->orderLabel() }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ $order->created_at->format('d/m/Y') }} · {{ $order->created_at->format('H.i') }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="text-xs capitalize font-medium text-gray-700">{{ $order->payment_method }}</span>
                                        @if($order->bank_name)
                                        <div class="text-[9px] text-gray-400">{{ $order->bank_name }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="text-xs font-bold text-[#1C1F1E]">{{ $order->formattedTotal() }}</div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium {{ $badge['bg'] }} {{ $badge['text'] }}">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <a href="{{ route('laporan.index', ['order_id' => $order->id, 'from' => $dateFrom, 'to' => $dateTo]) }}"
                                           class="text-[11px] font-medium {{ $selectedOrder && $selectedOrder->id === $order->id ? 'text-[#244B3B] font-bold underline' : 'text-gray-500 hover:text-[#244B3B]' }} mr-2">
                                            {{ $selectedOrder && $selectedOrder->id === $order->id ? 'Terpilih' : 'Pilih' }}
                                        </a>
                                        <a href="{{ route('transaksi.struk', $order) }}" target="_blank"
                                           class="text-[11px] text-[#244B3B] font-medium hover:underline">
                                            Lihat struk
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-400">
                                        <div class="text-2xl mb-1">📋</div>
                                        <p class="text-xs">Tidak ada transaksi pada periode ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-5 py-3 border-t border-[#E2DDD6] flex items-center justify-between">
                        <span class="text-xs text-gray-400">
                            {{ $orders->total() > 0 ? $orders->firstItem() . '–' . $orders->lastItem() . ' dari ' . $orders->total() . ' transaksi' : '0 transaksi' }}
                        </span>
                        <div class="flex gap-1">
                            @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            <a href="{{ $url }}"
                               class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-medium transition-colors {{ $orders->currentPage() === $page ? 'bg-[#244B3B] text-white' : 'bg-white border border-[#E2DDD6] text-gray-600 hover:bg-[#F5F3EE]' }}">
                                {{ $page }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ringkasan metode pembayaran -->
                <div class="bg-white rounded-xl border border-[#E2DDD6] mt-4 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-[#1C1F1E]">Ringkasan metode pembayaran</h3>
                        <span class="text-xs text-gray-400">{{ $totalTransaksi }} transaksi</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach(['tunai' => 'Tunai', 'qris' => 'QRIS', 'debit' => 'Debit'] as $key => $label)
                        @php $summary = $paymentSummary[$key] ?? null; @endphp
                        <div>
                            <div class="text-xs text-gray-500 mb-1">{{ $label }} · {{ $summary ? $summary->jumlah : 0 }} transaksi</div>
                            <div class="text-sm font-bold text-[#1C1F1E]">Rp{{ $summary ? number_format($summary->total_amount, 0, ',', '.') : 0 }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pratinjau Struk -->
            <div class="w-72 flex-shrink-0">
                <div class="bg-white rounded-xl border border-[#E2DDD6] overflow-hidden sticky top-0">
                    <div class="px-4 py-3 border-b border-[#E2DDD6] flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[#1C1F1E]">Pratinjau struk</h3>
                        <span class="text-[10px] text-gray-400 bg-[#F5F3EE] px-2 py-0.5 rounded">Nota 80 mm</span>
                    </div>

                    @if($selectedOrder)
                    <div class="p-4">
                        <!-- Mini struk preview -->
                        <div class="bg-[#FAFAF8] rounded-lg p-4 border border-[#E2DDD6] text-[11px] font-mono" style="line-height:1.7">
                            <div class="text-center border-b border-dashed border-gray-300 pb-3 mb-3">
                                <div class="flex justify-center mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-[#244B3B]">
                                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-xs tracking-wider">KOPI KITA</div>
                                <div class="text-[9px] text-gray-500">Jl. Cihapit No. 18, Bandung</div>
                                <div class="text-[9px] text-gray-500">Telp. 022-7281845</div>
                            </div>
                            <div class="border-b border-dashed border-gray-300 pb-2 mb-2 space-y-0.5">
                                <div class="flex justify-between"><span class="text-gray-500">Nota</span><span class="font-semibold">{{ $selectedOrder->orderLabel() }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Tanggal</span><span>{{ $selectedOrder->created_at->format('d/m/Y H.i') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Kasir</span><span>{{ $selectedOrder->user->name }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Layanan</span><span>{{ $selectedOrder->locationLabel() }}</span></div>
                            </div>
                            <div class="border-b border-dashed border-gray-300 pb-2 mb-2 space-y-1">
                                @foreach($selectedOrder->items as $item)
                                <div>
                                    <div class="font-medium">{{ $item->product->name }}</div>
                                    <div class="flex justify-between text-gray-500">
                                        <span>{{ $item->quantity }} x Rp{{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                        <span class="font-semibold text-[#1C1F1E]">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="border-b border-dashed border-gray-300 pb-2 mb-2 space-y-0.5">
                                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp{{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Pajak 10%</span><span>Rp{{ number_format($selectedOrder->tax, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between font-bold pt-0.5"><span>TOTAL</span><span>{{ $selectedOrder->formattedTotal() }}</span></div>
                            </div>
                            @if($selectedOrder->payment_method === 'tunai')
                            <div class="border-b border-dashed border-gray-300 pb-2 mb-2 space-y-0.5">
                                <div class="flex justify-between"><span class="text-gray-500">Tunai</span><span>Rp{{ number_format($selectedOrder->cash_received, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Kembalian</span><span>Rp{{ number_format($selectedOrder->cash_change, 0, ',', '.') }}</span></div>
                            </div>
                            @endif
                            <div class="text-center">
                                <div class="font-bold text-[#244B3B] tracking-widest text-xs">LUNAS</div>
                                <p class="text-[9px] text-gray-400 mt-0.5">Terima kasih sudah singgah.<br>Sampai di cangkir berikutnya!</p>
                            </div>
                        </div>

                        <!-- Cetak -->
                        <a href="{{ route('transaksi.struk', $selectedOrder) }}" target="_blank"
                           class="mt-3 w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-lg font-medium text-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            Cetak struk {{ $selectedOrder->orderLabel() }}
                        </a>
                    </div>
                    @else
                    <div class="p-10 text-center text-gray-400">
                        <div class="text-3xl mb-2">🧾</div>
                        <p class="text-xs">Tidak ada transaksi dipilih.<br>Pilih transaksi dari daftar riwayat.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
