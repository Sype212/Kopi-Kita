<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Tampilkan halaman POS transaksi kasir.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->paginate(8)->withQueryString();
        $totalProducts = Product::where('is_active', true)->count();
        $hiddenProducts = Product::where('is_active', false)->orWhere('stock', '<=', 0)->count();

        $nextOrderNumber = Order::generateOrderNumber();
        $qrisPath = 'qris/my-qris.png';
        $qrisUrl = Storage::disk('public')->exists($qrisPath)
            ? asset('storage/'.$qrisPath).'?v='.Storage::disk('public')->lastModified($qrisPath)
            : null;

        return view('transaksi.index', compact(
            'categories',
            'products',
            'totalProducts',
            'hiddenProducts',
            'nextOrderNumber',
            'qrisUrl',
        ));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_type' => ['required', 'in:dine_in,takeaway'],
            'table_number' => ['nullable', 'string'],
            'customer_name' => ['nullable', 'string'],
            'payment_method' => ['required', 'in:tunai,qris,debit'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'cash_received' => ['nullable', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak mencukupi. Stok tersisa: {$product->stock}",
                    ], 422);
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $tax = (int) round($subtotal * 0.1);
            $total = $subtotal + $tax;

            $cashReceived = $validated['cash_received'] ?? null;
            $cashChange = ($validated['payment_method'] === 'tunai' && $cashReceived)
                ? $cashReceived - $total
                : null;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'order_type' => $validated['order_type'],
                'table_number' => $validated['order_type'] === 'dine_in' ? ($validated['table_number'] ?? null) : null,
                'customer_name' => $validated['customer_name'] ?? 'Pelanggan umum',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'cash_received' => $cashReceived,
                'cash_change' => $cashChange,
                'status' => 'diproses',
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok produk
                $item['product']->decrement('stock', $item['quantity']);

                // Nonaktifkan produk jika stok habis
                if ($item['product']->fresh()->stock <= 0) {
                    $item['product']->update(['is_active' => false]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload custom QRIS image.
     */
    public function uploadQris(Request $request): JsonResponse
    {
        $request->validate([
            'qris_image' => ['required', 'image', 'max:4096'],
        ]);

        $path = $request->file('qris_image')->storeAs('qris', 'my-qris.png', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path).'?v='.time(),
        ]);
    }

    /**
     * Tampilkan struk transaksi (untuk cetak / preview).
     */
    public function struk(Order $order): View
    {
        $order->load(['items.product', 'user']);

        return view('transaksi.struk', compact('order'));
    }
}
