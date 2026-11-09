<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan & struk.
     */
    public function index(Request $request): View
    {
        $dateFrom = $request->input('from', now()->toDateString());
        $dateTo = $request->input('to', now()->toDateString());

        $orders = Order::with(['items.product', 'user'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPenjualan = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('status', '!=', 'dibatalkan')
            ->sum('total');

        $totalTransaksi = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('status', '!=', 'dibatalkan')
            ->count();

        $totalItem = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->with('items')
            ->get()
            ->flatMap(fn ($o) => $o->items)
            ->sum('quantity');

        $rataRataTransaksi = $totalTransaksi > 0
            ? (int) ($totalPenjualan / $totalTransaksi)
            : 0;

        // Ringkasan per metode pembayaran
        $paymentSummary = Order::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('status', '!=', 'dibatalkan')
            ->selectRaw('payment_method, COUNT(*) as jumlah, SUM(total) as total_amount')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Pesanan yang dipilih untuk pratinjau struk
        $selectedOrderId = $request->input('order_id');
        $selectedOrder = $selectedOrderId
            ? Order::with(['items.product', 'user'])->find($selectedOrderId)
            : $orders->first()?->load(['items.product', 'user']);

        return view('laporan.index', compact(
            'orders',
            'totalPenjualan',
            'totalTransaksi',
            'totalItem',
            'rataRataTransaksi',
            'paymentSummary',
            'selectedOrder',
            'dateFrom',
            'dateTo',
        ));
    }

    /**
     * Halaman cetak laporan resmi A4 / PDF.
     */
    public function cetak(Request $request): View
    {
        $dateFrom = $request->input('from', now()->toDateString());
        $dateTo = $request->input('to', now()->toDateString());

        $orders = Order::with(['items.product', 'user'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('id', 'asc')
            ->get();

        $totalPenjualan = $orders->sum('total');
        $totalTransaksi = $orders->count();
        $totalItem = $orders->flatMap(fn ($o) => $o->items)->sum('quantity');
        $rataRataTransaksi = $totalTransaksi > 0 ? (int) ($totalPenjualan / $totalTransaksi) : 0;

        $paymentSummary = $orders->groupBy('payment_method')->map(function ($items, $method) {
            return (object) [
                'jumlah' => $items->count(),
                'total_amount' => $items->sum('total'),
            ];
        });

        return view('laporan.cetak', compact(
            'orders',
            'totalPenjualan',
            'totalTransaksi',
            'totalItem',
            'rataRataTransaksi',
            'paymentSummary',
            'dateFrom',
            'dateTo',
        ));
    }

    /**
     * Unduh laporan dalam format CSV / Excel.
     */
    public function exportCsv(Request $request)
    {
        $dateFrom = $request->input('from', now()->toDateString());
        $dateTo = $request->input('to', now()->toDateString());

        $orders = Order::with(['items.product', 'user'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('id', 'asc')
            ->get();

        $filename = "Laporan_Penjualan_Kopi_Kita_{$dateFrom}_sd_{$dateTo}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($orders, $dateFrom, $dateTo) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Header Dokumen
            fputcsv($handle, ['KOPI KITA - LAPORAN PENJUALAN']);
            fputcsv($handle, ['Periode', "{$dateFrom} s/d {$dateTo}"]);
            fputcsv($handle, ['Dicetak Pada', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            // Header Tabel
            fputcsv($handle, [
                'No.',
                'No. Nota',
                'Tanggal & Waktu',
                'Kasir',
                'Tipe Layanan',
                'Metode Bayar',
                'Detail EDC / Bank',
                'Daftar Menu & Qty',
                'Subtotal (Rp)',
                'Pajak 10% (Rp)',
                'Total Bayar (Rp)',
                'Status',
            ]);

            $totalOmset = 0;
            $totalTax = 0;
            $totalSub = 0;

            foreach ($orders as $index => $order) {
                $itemSummary = $order->items->map(function ($i) {
                    return "{$i->product->name} (x{$i->quantity})";
                })->implode(', ');

                $totalSub += $order->subtotal;
                $totalTax += $order->tax;
                $totalOmset += $order->total;

                $edcDetail = '';
                if ($order->payment_method === 'debit') {
                    $edcDetail = ($order->bank_name ?? 'EDC').($order->reference_number ? ' - '.$order->reference_number : '');
                }

                fputcsv($handle, [
                    $index + 1,
                    $order->orderLabel(),
                    $order->created_at->format('d/m/Y H:i'),
                    $order->user->name ?? '-',
                    $order->locationLabel(),
                    strtoupper($order->payment_method),
                    $edcDetail,
                    $itemSummary,
                    $order->subtotal,
                    $order->tax,
                    $order->total,
                    strtoupper($order->status),
                ]);
            }

            // Total Baris
            fputcsv($handle, []);
            fputcsv($handle, ['TOTAL KESELURUHAN', '', '', '', '', '', '', '', $totalSub, $totalTax, $totalOmset, count($orders).' Transaksi']);

            fclose($handle);
        }, 200, $headers);
    }
}
