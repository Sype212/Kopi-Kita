<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan ({{ $dateFrom }} sd {{ $dateTo }}) – Kopi Kita</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f3f4f6;
            color: #1C1F1E;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm 12mm;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .report-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="p-6 min-h-screen flex flex-col items-center">
    <!-- Action Bar -->
    <div class="no-print w-full max-w-4xl mb-6 flex items-center justify-between bg-white p-4 rounded-xl border border-[#E2DDD6] shadow-sm">
        <div>
            <h2 class="text-sm font-bold text-[#1C1F1E]">Pratinjau Cetak Laporan Resmi (A4)</h2>
            <p class="text-xs text-gray-400">Periode: {{ \Carbon\Carbon::parse($dateFrom)->locale('id')->isoFormat('D MMMM YYYY') }} s/d {{ \Carbon\Carbon::parse($dateTo)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                class="flex items-center gap-2 px-4 py-2 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-lg text-xs font-semibold shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Cetak / Simpan PDF
            </button>
            <a href="{{ route('laporan.export-csv', ['from' => $dateFrom, 'to' => $dateTo]) }}"
                class="flex items-center gap-2 px-3 py-2 border border-[#244B3B] text-[#244B3B] hover:bg-[#244B3B]/10 rounded-lg text-xs font-semibold transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Unduh Excel (CSV)
            </a>
            <button onclick="window.close()"
                class="px-3 py-2 border border-[#E2DDD6] text-gray-600 hover:bg-gray-50 rounded-lg text-xs font-medium transition-colors">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Report Paper (A4) -->
    <div class="report-card w-full max-w-4xl bg-white rounded-xl shadow-md border border-[#E2DDD6] p-8 text-xs">
        <!-- Letterhead Header -->
        <div class="flex items-start justify-between border-b-2 border-[#244B3B] pb-4 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#244B3B] text-white flex items-center justify-center font-bold text-lg">
                    ☕
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-[#1C1F1E]">KOPI KITA</h1>
                    <p class="text-[11px] text-gray-500">Sistem Informasi Kasir & Rekapitulasi Operasional</p>
                    <p class="text-[10px] text-gray-400">Jl. Cihapit No. 18, Bandung • Telp. 022-7281845</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs font-bold uppercase text-[#244B3B] tracking-wider">Laporan Penjualan</div>
                <div class="text-[11px] text-gray-600 font-medium mt-0.5">
                    Periode: {{ \Carbon\Carbon::parse($dateFrom)->locale('id')->isoFormat('D MMM YYYY') }} – {{ \Carbon\Carbon::parse($dateTo)->locale('id')->isoFormat('D MMM YYYY') }}
                </div>
                <div class="text-[10px] text-gray-400">Dicetak: {{ now()->format('d/m/Y H.i') }} WIB</div>
            </div>
        </div>

        <!-- KPI Ringkasan Box -->
        <div class="grid grid-cols-4 gap-3 mb-5">
            <div class="bg-[#F8FAF9] border border-[#244B3B]/20 rounded-lg p-3">
                <div class="text-[10px] text-gray-500 font-medium">Total Penjualan</div>
                <div class="text-base font-bold text-[#244B3B] mt-0.5">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                <div class="text-[9px] text-gray-400">Termasuk pajak 10%</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-[10px] text-gray-500 font-medium">Transaksi Selesai</div>
                <div class="text-base font-bold text-[#1C1F1E] mt-0.5">{{ $totalTransaksi }} Transaksi</div>
                <div class="text-[9px] text-gray-400">100% tervalidasi</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-[10px] text-gray-500 font-medium">Total Item Terjual</div>
                <div class="text-base font-bold text-[#1C1F1E] mt-0.5">{{ $totalItem }} Item</div>
                <div class="text-[9px] text-gray-400">Kopi & Makanan</div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="text-[10px] text-gray-500 font-medium">Rata-rata / Transaksi</div>
                <div class="text-base font-bold text-[#1C1F1E] mt-0.5">Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
                <div class="text-[9px] text-gray-400">Nilai rata-rata nota</div>
            </div>
        </div>

        <!-- Ringkasan Metode Bayar -->
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 mb-5">
            <div class="text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-2">Rekapitulasi Pembayaran</div>
            <div class="grid grid-cols-3 gap-4">
                @foreach(['tunai' => 'Tunai', 'qris' => 'QRIS', 'debit' => 'Debit'] as $key => $label)
                @php $summary = $paymentSummary->get($key); @endphp
                <div class="flex items-center justify-between border-r last:border-0 border-gray-200 pr-3">
                    <div>
                        <div class="text-[11px] font-semibold text-[#1C1F1E]">{{ $label }}</div>
                        <div class="text-[9px] text-gray-400">{{ $summary ? $summary->jumlah : 0 }} transaksi</div>
                    </div>
                    <div class="text-xs font-bold text-[#244B3B]">
                        Rp{{ $summary ? number_format($summary->total_amount, 0, ',', '.') : 0 }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tabel Riwayat Transaksi -->
        <div class="mb-6">
            <div class="text-[11px] font-bold text-[#1C1F1E] uppercase tracking-wider mb-2">Rincian Transaksi Penjualan</div>
            <table class="w-full text-[10px] border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-[#F5F3EE] text-[#1C1F1E]">
                        <th class="border border-gray-300 px-2 py-1.5 text-center w-8">No</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">No. Nota</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Waktu</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Kasir</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Layanan</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Metode</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Menu yang Dibeli</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-right">Subtotal</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-right">Pajak</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-2 py-1.5 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-2 py-1.5 font-bold text-[#1C1F1E]">{{ $order->orderLabel() }}</td>
                        <td class="border border-gray-300 px-2 py-1.5">{{ $order->created_at->format('d/m H.i') }}</td>
                        <td class="border border-gray-300 px-2 py-1.5">{{ $order->user->name ?? '-' }}</td>
                        <td class="border border-gray-300 px-2 py-1.5">{{ $order->locationLabel() }}</td>
                        <td class="border border-gray-300 px-2 py-1.5 uppercase font-medium">
                            {{ $order->payment_method }}
                            @if($order->bank_name) ({{ $order->bank_name }}) @endif
                        </td>
                        <td class="border border-gray-300 px-2 py-1.5">
                            @foreach($order->items as $item)
                            <span>{{ $item->product->name }} (x{{ $item->quantity }}){{ !$loop->last ? ', ' : '' }}</span>
                            @endforeach
                        </td>
                        <td class="border border-gray-300 px-2 py-1.5 text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-2 py-1.5 text-right">Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-2 py-1.5 text-right font-bold text-[#1C1F1E]">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="border border-gray-300 py-6 text-center text-gray-400">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-[#F5F3EE] font-bold text-[#1C1F1E]">
                        <td colspan="7" class="border border-gray-300 px-3 py-2 text-right uppercase">Total Penjualan</td>
                        <td class="border border-gray-300 px-2 py-2 text-right">Rp{{ number_format($orders->sum('subtotal'), 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-2 py-2 text-right">Rp{{ number_format($orders->sum('tax'), 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-2 py-2 text-right text-[#244B3B] text-xs">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-10 mt-8 pt-4 border-t border-gray-200 text-center text-[10px]">
            <div>
                <p class="text-gray-500">Dibuat oleh (Kasir / Petugas):</p>
                <div class="h-16"></div>
                <p class="font-bold text-[#1C1F1E] underline">{{ auth()->user()->name ?? 'Kasir Kopi Kita' }}</p>
                <p class="text-gray-400">Shift Operasional</p>
            </div>
            <div>
                <p class="text-gray-500">Disetujui oleh (Manager / Owner):</p>
                <div class="h-16"></div>
                <p class="font-bold text-[#1C1F1E] underline">Management Kopi Kita</p>
                <p class="text-gray-400">Kopi Kita • Bandung</p>
            </div>
        </div>
    </div>
</body>

</html>