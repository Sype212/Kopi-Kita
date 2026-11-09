<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama.
     */
    public function index(): View
    {
        $today = now()->toDateString();

        // Statistik hari ini
        $totalPenjualanHariIni = Order::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->sum('total');

        $jumlahMenu = Product::count();
        $jumlahMenuTersedia = Product::where('is_active', true)->where('stock', '>', 0)->count();
        $jumlahMenuHabis = Product::where('stock', '<=', 0)->count();

        $pesananHariIni = Order::whereDate('created_at', $today)->count();
        $totalItemTerjual = Order::whereDate('created_at', $today)
            ->with('items')
            ->get()
            ->flatMap(fn ($o) => $o->items)
            ->sum('quantity');

        $rataRataTransaksi = $pesananHariIni > 0
            ? (int) ($totalPenjualanHariIni / $pesananHariIni)
            : 0;

        // Data grafik penjualan per jam
        $salesPerHour = [];
        for ($hour = 8; $hour <= 14; $hour++) {
            $amount = (int) Order::whereDate('created_at', $today)
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->sum('total');
            $salesPerHour[] = [
                'hour' => sprintf('%02d.00', $hour),
                'amount' => $amount,
                'label' => $amount > 0 ? number_format($amount / 1000, 0).'rb' : '0',
            ];
        }

        // Ringkasan pesanan terbaru
        $recentOrders = Order::with(['items'])
            ->latest()
            ->take(8)
            ->get();

        // Menu paling banyak dipesan hari ini
        $topMenus = Product::withCount(['orderItems as total_sold' => function ($q) use ($today) {
            $q->whereHas('order', fn ($o) => $o->whereDate('created_at', $today));
        }])
            ->orderByDesc('total_sold')
            ->take(3)
            ->get();

        // Produk stok menipis
        $lowStockProduct = Product::where('stock', '>', 0)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->first();

        return view('dashboard', compact(
            'totalPenjualanHariIni',
            'jumlahMenu',
            'jumlahMenuTersedia',
            'jumlahMenuHabis',
            'pesananHariIni',
            'totalItemTerjual',
            'rataRataTransaksi',
            'salesPerHour',
            'recentOrders',
            'topMenus',
            'lowStockProduct',
        ));
    }
}
