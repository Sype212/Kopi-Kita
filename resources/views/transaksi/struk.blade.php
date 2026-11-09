<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->order_number }} – Kopi Kita</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', monospace; background: #f3f4f6; }
        @media print {
            @page {
                margin: 4mm;
                size: auto;
            }
            html, body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                min-height: auto !important;
                height: auto !important;
                display: block !important;
            }
            .no-print {
                display: none !important;
            }
            .struk-container {
                padding: 0 !important;
                margin: 0 auto !important;
            }
            .struk-card {
                box-shadow: none !important;
                border: none !important;
                padding: 8px !important;
            }
            /* Jika mode full aktif saat cetak */
            .mode-full .struk-container {
                max-width: 100% !important;
                width: 100% !important;
            }
            .mode-full .struk-card {
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body id="pageBody" class="min-h-screen py-8 px-4 flex flex-col items-center">
    <!-- Action Bar (No Print) -->
    <div class="no-print w-full max-w-md mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-[#E2DDD6] p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 bg-[#F5F3EE] p-1 rounded-lg">
                <button type="button" onclick="setReceiptMode('compact')" id="btnCompact"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold bg-white shadow-xs text-[#244B3B] transition-all">
                    🧾 Thermal 80mm
                </button>
                <button type="button" onclick="setReceiptMode('full')" id="btnFull"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold text-gray-500 hover:text-gray-700 transition-all">
                    📄 Lebar Penuh (A4)
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()"
                        class="flex items-center gap-1.5 px-4 py-2 bg-[#244B3B] text-white rounded-lg text-xs font-semibold hover:bg-[#1a3a2d] transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    Cetak Struk
                </button>
                <a href="{{ route('transaksi.index') }}"
                   class="px-3 py-2 border border-[#E2DDD6] text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
                    Transaksi baru
                </a>
            </div>
        </div>
    </div>

    <!-- Container Struk -->
    <div id="receiptContainer" class="struk-container w-full max-w-xs transition-all duration-300">
        <div class="struk-card bg-white rounded-2xl shadow-md border border-[#E2DDD6] p-6 text-xs text-[#1C1F1E]">
            <!-- Header Logo & Kafe -->
            <div class="text-center border-b border-dashed border-gray-300 pb-4 mb-4">
                <div class="flex justify-center mb-1.5">
                    <div class="w-8 h-8 rounded-full bg-[#244B3B]/10 flex items-center justify-center text-[#244B3B]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                            <line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
                        </svg>
                    </div>
                </div>
                <div class="font-bold text-base tracking-wider text-[#1C1F1E]">KOPI KITA</div>
                <div class="text-[10px] text-gray-500">Jl. Cihapit No. 18, Bandung</div>
                <div class="text-[10px] text-gray-500">Telp. 022-7281845</div>
            </div>

            <!-- Detail Info Nota -->
            <div class="border-b border-dashed border-gray-300 pb-3 mb-3 space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Nota</span>
                    <span class="font-bold text-[#1C1F1E]">{{ $order->orderLabel() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span>{{ $order->created_at->format('d/m/Y H.i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kasir</span>
                    <span>{{ $order->user->name ?? 'Kasir' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Layanan</span>
                    <span class="font-medium">{{ $order->locationLabel() }}</span>
                </div>
            </div>

            <!-- Daftar Item Pesanan -->
            <div class="border-b border-dashed border-gray-300 pb-3 mb-3 space-y-2 text-[11px]">
                @foreach($order->items as $item)
                <div>
                    <div class="font-semibold text-[#1C1F1E]">{{ $item->product->name }}</div>
                    <div class="flex justify-between text-gray-500 text-[10px] mt-0.5">
                        <span>{{ $item->quantity }} x {{ $item->formattedUnitPrice() }}</span>
                        <span class="font-semibold text-[#1C1F1E] text-[11px]">{{ $item->formattedSubtotal() }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Perhitungan Total -->
            <div class="border-b border-dashed border-gray-300 pb-3 mb-3 space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pajak (10%)</span>
                    <span>Rp{{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-sm text-[#1C1F1E] pt-1">
                    <span>TOTAL</span>
                    <span class="text-[#244B3B] text-base">{{ $order->formattedTotal() }}</span>
                </div>
            </div>

            <!-- Rincian Pembayaran (Tunai / QRIS / Debit) -->
            <div class="border-b border-dashed border-gray-300 pb-3 mb-4 space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode Bayar</span>
                    <span class="font-bold uppercase text-[#244B3B]">{{ $order->payment_method }}</span>
                </div>

                @if($order->payment_method === 'tunai')
                    <div class="flex justify-between">
                        <span class="text-gray-500">Uang Diterima</span>
                        <span>Rp{{ number_format($order->cash_received, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-medium">
                        <span class="text-gray-500">Kembalian</span>
                        <span class="text-[#244B3B]">Rp{{ number_format($order->cash_change, 0, ',', '.') }}</span>
                    </div>
                @elseif($order->payment_method === 'debit')
                    @if($order->bank_name)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Mesin / Bank</span>
                        <span class="font-semibold">{{ $order->bank_name }}</span>
                    </div>
                    @endif
                    @if($order->reference_number)
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Ref / Kartu</span>
                        <span class="font-mono text-[10px]">{{ $order->reference_number }}</span>
                    </div>
                    @endif
                @elseif($order->payment_method === 'qris')
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status Settlement</span>
                        <span class="text-green-600 font-semibold">Berhasil (QRIS)</span>
                    </div>
                @endif
            </div>

            <!-- Footer Struk -->
            <div class="text-center space-y-1">
                <div class="inline-block px-3 py-0.5 rounded-full bg-green-50 text-[#244B3B] font-bold tracking-widest text-[10px] border border-green-200">
                    LUNAS
                </div>
                <p class="text-gray-500 text-[10px] mt-2">Terima kasih sudah singgah.<br>Sampai jumpa di cangkir berikutnya!</p>
                <div class="text-[9px] text-gray-400 font-mono mt-1">#{{ $order->order_number }} • KOPI KITA POS</div>
            </div>
        </div>
    </div>

    <script>
        function setReceiptMode(mode) {
            const container = document.getElementById('receiptContainer');
            const pageBody = document.getElementById('pageBody');
            const btnCompact = document.getElementById('btnCompact');
            const btnFull = document.getElementById('btnFull');

            if (mode === 'full') {
                container.className = 'struk-container w-full max-w-xl transition-all duration-300';
                pageBody.classList.add('mode-full');
                btnFull.className = 'px-3 py-1.5 rounded-md text-xs font-semibold bg-white shadow-xs text-[#244B3B] transition-all';
                btnCompact.className = 'px-3 py-1.5 rounded-md text-xs font-semibold text-gray-500 hover:text-gray-700 transition-all';
            } else {
                container.className = 'struk-container w-full max-w-xs transition-all duration-300';
                pageBody.classList.remove('mode-full');
                btnCompact.className = 'px-3 py-1.5 rounded-md text-xs font-semibold bg-white shadow-xs text-[#244B3B] transition-all';
                btnFull.className = 'px-3 py-1.5 rounded-md text-xs font-semibold text-gray-500 hover:text-gray-700 transition-all';
            }
        }
    </script>
</body>
</html>
